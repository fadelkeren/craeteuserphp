/*
bikin database dlu dengan nama => simple_app
setelah bikin database, pergi ke menu tab SQL di bagian atas databased
paste code ini ke terminal sqlnya =>
*/

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*
lalu submit dengan cara tekan go/kirim dikanan bawah
*/
