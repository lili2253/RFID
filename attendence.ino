#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>

#define SS_PIN D2
#define RST_PIN D1
MFRC522 mfrc522(SS_PIN, RST_PIN);

const char* ssid = "Zaw Linn Htun"; // Your WiFi SSID
const char* password = "@5FeuGsX7#"; // Your WiFi Password

void setup() {
  Serial.begin(115200);
  SPI.begin();
  mfrc522.PCD_Init();
  
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("Connected to WiFi");
}

void loop() {
  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
    return;
  }
  
  String uid = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    uid += String(mfrc522.uid.uidByte[i] < 0x10 ? "0" : "");
    uid += String(mfrc522.uid.uidByte[i], HEX);
  }
  uid.toUpperCase();
  
  Serial.println("Card UID: " + uid);
  
  sendUIDToServer(uid);
  
  mfrc522.PICC_HaltA();
}

void sendUIDToServer(String uid) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin("http://192.168.1.6/rfid/record.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
    String postData = "uid=" + uid;
    int httpCode = http.POST(postData);
    
    if (httpCode > 0) {
      String payload = http.getString();
      Serial.println("Server Response: " + payload);
    } else {
      Serial.println("Error in sending request");
    }
    
    http.end();
  }
}
