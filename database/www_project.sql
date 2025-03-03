create database www_project
use www_project
drop database www_project
use QLMB
-- Nếu chưa tồn tại bảng admin thì tạo bảng
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'admin')
BEGIN
    CREATE TABLE admin (
        [name] VARCHAR(20) COLLATE Latin1_General_CI_AS NOT NULL,
        [pass] VARCHAR(40) COLLATE Latin1_General_CI_AS NOT NULL,
        CONSTRAINT PK_admin PRIMARY KEY ([name], [pass])
    );
END
GO

-- Chèn dữ liệu
INSERT INTO admin ([name], [pass])
VALUES ('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997');
GO
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'books')
BEGIN
    CREATE TABLE books (
        book_isbn   VARCHAR(20) NOT NULL PRIMARY KEY,
        book_title  VARCHAR(60) NULL,
        book_author VARCHAR(60) NULL,
        book_image  VARCHAR(40) NULL,
        book_descr  VARCHAR(MAX) NULL,
        book_price  DECIMAL(6,2) NOT NULL,
        publisherid INT NOT NULL  -- SQL Server không có kiểu unsigned, dùng INT
    );
END
GO

-- Chèn dữ liệu vào bảng books
INSERT INTO books (book_isbn, book_title, book_author, book_image, book_descr, book_price, publisherid)
VALUES 
('978-0-321-94786-4', 
 'Learning Mobile App Development', 
 'Jakob Iversen, Michael Eierman', 
 'mobile_app.jpg', 
 'Now, one book can help you master mobile app development with both market-leading platforms: Apple''s iOS and Google''s Android. Perfect for both students and professionals, Learning Mobile App Development is the only tutorial with complete parallel coverage of both iOS and Android. With this guide, you can master either platform, or both - and gain a deeper understanding of the issues associated with developing mobile apps.
You''ll develop an actual working app on both iOS and Android, mastering the entire mobile app development lifecycle, from planning through licensing and distribution.
Each tutorial in this book has been carefully designed to support readers with widely varying backgrounds and has been extensively tested in live developer training courses. If you''re new to iOS, you''ll also find an easy, practical introduction to Objective-C, Apple''s native language.', 
 '20.00', 
 6),
('978-0-7303-1484-4', 
 'Doing Good By Doing Good', 
 'Peter Baines', 
 'doing_good.jpg', 
 'Doing Good by Doing Good shows companies how to improve the bottom line by implementing an engaging, authentic, and business-enhancing program that helps staff and business thrive. International CSR consultant Peter Baines draws upon lessons learnt from the challenges faced in his career as a police officer, forensic investigator, and founder of Hands Across the Water to describe the Australian CSR landscape, and the factors that make up a program that benefits everyone involved. Case studies illustrate the real effect of CSR on both business and society, with clear guidance toward maximizing involvement, engaging all employees, and improving the bottom line.', 
 '20.00', 
 2),
('978-1-118-94924-5', 
 'Programmable Logic Controllers', 
 'Dag H. Hanssen', 
 'logic_program.jpg', 
 'Widely used across industrial and manufacturing automation, Programmable Logic Controllers (PLCs) perform a broad range of electromechanical tasks with multiple input and output arrangements, designed specifically to cope in severe environmental conditions such as automotive and chemical plants.Programmable Logic Controllers: A Practical Approach using CoDeSys is a hands-on guide to rapidly gain proficiency in the development and operation of PLCs based on the IEC 61131-3 standard. Using the freely-available* software tool CoDeSys, which is widely used in industrial design automation projects, the author takes a highly practical approach to PLC design using real-world examples. The design tool, CoDeSys, also features a built in simulator / soft PLC enabling the reader to undertake exercises and test the examples.', 
 '20.00', 
 2),
('978-1-1180-2669-4', 
 'Professional JavaScript for Web Developers, 3rd Edition', 
 'Nicholas C. Zakas', 
 'pro_js.jpg', 
 'If you want to achieve JavaScript''s full potential, it is critical to understand its nature, history, and limitations. To that end, this updated version of the bestseller by veteran author and JavaScript guru Nicholas C. Zakas covers JavaScript from its very beginning to the present-day incarnations including the DOM, Ajax, and HTML5. Zakas shows you how to extend this powerful language to meet specific needs and create dynamic user interfaces for the web that blur the line between desktop and internet. By the end of the book, you''ll have a strong understanding of the significant advances in web development as they relate to JavaScript so that you can apply them to your next website.', 
 '20.00', 
 1),
('978-1-44937-019-0', 
 'Learning Web App Development', 
 'Semmy Purewal', 
 'web_app_dev.jpg', 
 'Grasp the fundamentals of web application development by building a simple database-backed app from scratch, using HTML, JavaScript, and other open source tools. Through hands-on tutorials, this practical guide shows inexperienced web app developers how to create a user interface, write a server, build client-server communication, and use a cloud-based service to deploy the application.
Each chapter includes practice problems, full examples, and mental models of the development workflow. Ideal for a college-level course, this book helps you get started with web app development by providing you with a solid grounding in the process.', 
 '20.00', 
 3),
('978-1-44937-075-6', 
 'Beautiful JavaScript', 
 'Anton Kovalyov', 
 'beauty_js.jpg', 
 'JavaScript is arguably the most polarizing and misunderstood programming language in the world. Many have attempted to replace it as the language of the Web, but JavaScript has survived, evolved, and thrived. Why did a language created in such hurry succeed where others failed?
This guide gives you a rare glimpse into JavaScript from people intimately familiar with it. Chapters contributed by domain experts such as Jacob Thornton, Ariya Hidayat, and Sara Chipps show what they love about their favorite language - whether it''s turning the most feared features into useful tools, or how JavaScript can be used for self-expression.', 
 '20.00', 
 3),
('978-1-4571-0402-2', 
 'Professional ASP.NET 4 in C# and VB', 
 'Scott Hanselman', 
 'pro_asp4.jpg', 
 'ASP.NET is about making you as productive as possible when building fast and secure web applications. Each release of ASP.NET gets better and removes a lot of the tedious code that you previously needed to put in place, making common ASP.NET tasks easier. With this book, an unparalleled team of authors walks you through the full breadth of ASP.NET and the new and exciting capabilities of ASP.NET 4. The authors also show you how to maximize the abundance of features that ASP.NET offers to make your development process smoother and more efficient.', 
 '20.00', 
 1),
('978-1-484216-40-8', 
 'Android Studio New Media Fundamentals', 
 'Wallace Jackson', 
 'android_studio.jpg', 
 'Android Studio New Media Fundamentals is a new media primer covering concepts central to multimedia production for Android including digital imagery, digital audio, digital video, digital illustration and 3D, using open source software packages such as GIMP, Audacity, Blender, and Inkscape. These professional software packages are used for this book because they are free for commercial use. The book builds on the foundational concepts of raster, vector, and waveform (audio), and gets more advanced as chapters progress, covering what new media assets are best for use with Android Studio as well as key factors regarding the data footprint optimization work process and why new media content and new media data optimization is so important.', 
 '20.00', 
 4),
('978-1-484217-26-9', 
 'C++ 14 Quick Syntax Reference, 2nd Edition', 
 'Mikael Olsson', 
 'c_14_quick.jpg', 
 'This updated handy quick C++ 14 guide is a condensed code and syntax reference based on the newly updated C++ 14 release of the popular programming language. It presents the essential C++ syntax in a well-organized format that can be used as a handy reference.
You won''t find any technical jargon, bloated samples, drawn out history lessons, or witty stories in this book. What you will find is a language reference that is concise, to the point and highly accessible. The book is packed with useful information and is a must-have for any C++ programmer.
In the C++ 14 Quick Syntax Reference, Second Edition, you will find a concise reference to the C++ 14 language syntax. It has short, simple, and focused code examples. This book includes a well laid out table of contents and a comprehensive index allowing for easy review.', 
 '20.00', 
 4),
('978-1-49192-706-9', 
 'C# 6.0 in a Nutshell, 6th Edition', 
 'Joseph Albahari, Ben Albahari', 
 'c_sharp_6.jpg', 
 'When you have questions about C# 6.0 or the .NET CLR and its core Framework assemblies, this bestselling guide has the answers you need. C# has become a language of unusual flexibility and breadth since its premiere in 2000, but this continual growth means there''s still much more to learn.
Organized around concepts and use cases, this thoroughly updated sixth edition provides intermediate and advanced programmers with a concise map of C# and .NET knowledge. Dive in and discover why this Nutshell guide is considered the definitive reference on C#.', 
 '20.00', 
 3);
GO

IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'customers')
BEGIN
    CREATE TABLE customers (
        customerid INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
        [name] VARCHAR(60) NOT NULL,
        address VARCHAR(80) NOT NULL,
        city VARCHAR(30) NOT NULL,
        zip_code VARCHAR(10) NOT NULL,
        country VARCHAR(60) NOT NULL
    );
END
GO

-- Cho phép chèn giá trị tĩnh vào cột IDENTITY
SET IDENTITY_INSERT customers ON;
INSERT INTO customers (customerid, [name], address, city, zip_code, country)
VALUES 
(1, 'a', 'a', 'a', 'a', 'a'),
(2, 'b', 'b', 'b', 'b', 'b'),
(3, 'test', '123 test', '12121', 'test', 'test');
SET IDENTITY_INSERT customers OFF;
GO

IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'orders')
BEGIN
    CREATE TABLE orders (
        orderid INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
        customerid INT NOT NULL,
        amount DECIMAL(6,2) NULL,
        [date] DATETIME NOT NULL DEFAULT GETDATE(),
        ship_name CHAR(60) NOT NULL,
        ship_address CHAR(80) NOT NULL,
        ship_city CHAR(30) NOT NULL,
        ship_zip_code CHAR(10) NOT NULL,
        ship_country CHAR(20) NOT NULL
    );
END
GO

-- Chèn dữ liệu cho orders (bỏ qua việc đặt giá trị orderid vì nó là IDENTITY)
SET IDENTITY_INSERT orders ON;
INSERT INTO orders (orderid, customerid, amount, [date], ship_name, ship_address, ship_city, ship_zip_code, ship_country)
VALUES 
(1, 1, 60.00, '2015-12-03 13:30:12', 'a', 'a', 'a', 'a', 'a'),
(2, 2, 60.00, '2015-12-03 13:31:12', 'b', 'b', 'b', 'b', 'b'),
(3, 3, 20.00, '2015-12-03 19:34:21', 'test', '123 test', '12121', 'test', 'test'),
(4, 1, 20.00, '2015-12-04 10:19:14', 'a', 'a', 'a', 'a', 'a');
SET IDENTITY_INSERT orders OFF;
GO

IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'order_items')
BEGIN
    CREATE TABLE order_items (
        orderid INT NOT NULL,
        book_isbn VARCHAR(20) NOT NULL,
        item_price DECIMAL(6,2) NOT NULL,
        quantity TINYINT NOT NULL,
        CONSTRAINT PK_order_items PRIMARY KEY (orderid, book_isbn)
    );
END
GO

INSERT INTO order_items (orderid, book_isbn, item_price, quantity)
VALUES 
(1, '978-1-118-94924-5', 20.00, 1),
(2, '978-1-44937-019-0', 20.00, 1),
(3, '978-1-49192-706-9', 20.00, 1),
(4, '978-1-118-94924-5', 20.00, 1),
(5, '978-1-44937-019-0', 20.00, 1),
(6, '978-1-49192-706-9', 20.00, 1),
(7, '978-0-321-94786-4', 20.00, 1),
(8, '978-1-49192-706-9', 20.00, 1);
GO

IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'publisher')
BEGIN
    CREATE TABLE publisher (
        publisherid INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
        publisher_name VARCHAR(60) NOT NULL
    );
END
GO

SET IDENTITY_INSERT publisher ON;
INSERT INTO publisher (publisherid, publisher_name)
VALUES 
(1, 'Wrox'),
(2, 'Wiley'),
(3, 'O''Reilly Media'),
(4, 'Apress'),
(5, 'Packt Publishing'),
(6, 'Addison-Wesley');
SET IDENTITY_INSERT publisher OFF;
GO

