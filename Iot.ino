#include <WiFi.h>
#include <HTTPClient.h>

// Pin TCS3200
const int S2 = 2;  // S2 ke pin 2
const int S3 = 4;  // S3 ke pin 4
const int OUT = 15; // OUT ke pin 15

// WiFi Credentials
const char* ssid = "Anas";
const char* password = "anaskhalifm";

// Endpoint URL
String endpoint = "http://15.235.207.39:8006/api/infaq";

// Nominal mapping berdasarkan warna uang Indonesia
int nominal = 0;

// Setup
void setup() {
  Serial.begin(115200);
  pinMode(S2, OUTPUT);
  pinMode(S3, OUTPUT);
  pinMode(OUT, INPUT);

  // Connect to WiFi
  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi...");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected to WiFi");
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());  // Menampilkan IP Address ESP32
  delay(2000);  // Tunggu beberapa detik setelah Wi-Fi terhubung sebelum membaca sensor
}

// Loop
void loop() {
  int colorValue = pulseIn(OUT, HIGH); // Membaca nilai sensor

  // Menampilkan nilai colorValue di Serial Monitor untuk kalibrasi
  Serial.println("Nilai Warna (Raw): " + String(colorValue));

  // Lakukan deteksi warna dan tentukan nominal uang berdasarkan warna
  if (colorValue > 35 && colorValue < 45) { // Deteksi warna hijau muda (Rp 1.000)
    nominal = 1000;
  } else if (colorValue > 45 && colorValue < 55) { // Deteksi warna ungu (Rp 2.000)
    nominal = 2000;
  } else if (colorValue > 55 && colorValue < 65) { // Deteksi warna oranye (Rp 5.000)
    nominal = 5000;
  } else if (colorValue > 65 && colorValue < 75) { // Deteksi warna ungu tua (Rp 10.000)
    nominal = 10000;
  } else if (colorValue > 75 && colorValue < 85) { // Deteksi warna hijau (Rp 20.000)
    nominal = 20000;
  } else if (colorValue > 85 && colorValue < 95) { // Deteksi warna biru (Rp 50.000)
    nominal = 50000;
  }

  // Mencetak nilai nominal yang terdeteksi
  Serial.println("Nominal yang terdeteksi: " + String(nominal));

  // Jika nominal terdeteksi, kirim ke endpoint
  if (nominal > 0) {
    sendToEndpoint(nominal);
    delay(5000); // Tunggu 5 detik sebelum mengirim lagi
  } else {
    delay(1000); // Jeda 1 detik jika tidak ada nominal yang terdeteksi
  }
}

// Fungsi untuk mengirim data ke endpoint
void sendToEndpoint(int amount) {
  HTTPClient http;
  String url = endpoint;

  http.begin(url);
  http.addHeader("Content-Type", "application/json");

  // Membuat body JSON
  String body = "{\"nominal\": " + String(amount) + "}";

  int httpResponseCode = http.POST(body); // Kirim data

  Serial.print("Kode Respon HTTP: ");
  Serial.println(httpResponseCode);  // Mencetak kode respon untuk melihat apa yang dikembalikan

  if (httpResponseCode > 0) {
    Serial.println("Data terkirim: " + String(body));
  } else {
    Serial.println("Error dalam pengiriman data: " + String(httpResponseCode));
  }

  http.end();
}
