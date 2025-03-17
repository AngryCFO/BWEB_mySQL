```sql
-- Создание таблицы для контактов
CREATE TABLE contacts (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    SecondName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Создание таблицы для заказов
CREATE TABLE orders (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ContactId INT NOT NULL,
    City VARCHAR(100) NOT NULL,
    Street VARCHAR(150) NOT NULL,
    House VARCHAR(50) NOT NULL,
    Flat VARCHAR(50) NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ContactId) REFERENCES contacts(ID)
);
```
