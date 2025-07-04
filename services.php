<?php include("header/header1.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Construction Company</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            flex: 1;
            text-align: center;
            padding: 60px 20px;
        }

        /* Hero Section */
        .hero {
            background: url("construction-banner.jpg") no-repeat center center/cover;
            color: black;
            text-align: center;
            padding: 100px 20px;
            position: relative;
        }

        .hero h1 {
            font-size: 42px;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .btn {
            padding: 12px 25px;
            background: #ff9900;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 18px;
            transition: 0.3s ease;
        }

        .btn:hover {
            background: #cc7700;
        }

        .services {
            color: black;
            padding: 50px 20px;
            text-align: center;
        }

        .service-container {
            color: #000;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .service-box {
            width: 320px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            transition: 0.3s ease;
        }

        .service-box:hover {
            transform: translateY(-5px);
        }

        .service-box img {
            width: 100%;
            border-radius: 10px;
        }

        .service-box h3 {
            font-size: 22px;
            margin: 15px 0;
        }

        .service-box p {
            font-size: 16px;
            opacity: 0.7;
        }

        
        .why-choose {
            background: #222;
            color: white;
            text-align: center;
            padding: 50px 20px;
        }

        .why-choose h2 {
            font-size: 30px;
            margin-bottom: 20px;
        }

        .why-list {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .why-box {
            width: 300px;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
        }

        .why-box i {
            font-size: 40px;
            color: #ff9900;
            margin-bottom: 10px;
        }

        
        .testimonials {
            color: #000;
            padding: 50px 20px;
            text-align: center;
        }

        .testimonial-card {
            width: 320px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px auto;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .testimonial-card img {
            width: 50px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .testimonial-card p {
            font-size: 16px;
            opacity: 0.8;
        }

        .footer {
            background: #222;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
        }

    </style>
</head>
<body>

 

    <section class="hero">
        <h1>Building the Future, Restoring the Past</h1>
        <p>We deliver high-quality construction services with a commitment to excellence.</p>
        <a href="contact.php" class="btn">Get a Free Quote</a>
    </section>

    <div class="container">
        <section class="services">
            <h2>Our Services</h2>
            <div class="service-container">
                <div class="service-box">
                    <img src="photo\image1.jpg" alt="Residential Construction">
                    <h3>Residential Construction</h3>
                    <p>Building modern, durable, and stylish homes with expert craftsmanship.</p>
                </div>
                <div class="service-box">
                    <img src="photo\image2.jpeg" alt="Commercial Construction">
                    <h3>Commercial Projects</h3>
                    <p>Designing and constructing commercial spaces that meet industry standards.</p>
                </div>
                <div class="service-box">
                    <img src="photo\image3.jpeg" alt="Renovation">
                    <h3>Renovation & Remodeling</h3>
                    <p>Transform your space with our expert remodeling and renovation services.</p>
                </div>
            </div>
        </section>

        <section class="why-choose">
            <h2>Why Choose Us?</h2>
            <div class="why-list">
                <div class="why-box">
                    <i class="fas fa-hard-hat"></i>
                    <h3>Expert Engineers</h3>
                    <p>Our team consists of highly skilled and experienced professionals.</p>
                </div>
                <div class="why-box">
                    <i class="fas fa-building"></i>
                    <h3>Quality Assurance</h3>
                    <p>We use the best materials and ensure the highest construction standards.</p>
                </div>
                <div class="why-box">
                    <i class="fas fa-clock"></i>
                    <h3>On-Time Delivery</h3>
                    <p>We value your time and ensure projects are completed within deadlines.</p>
                </div>
            </div>
        </section>

        <section class="testimonials">
            <h2>What Our Clients Say</h2>
            <div class="testimonial-card">
                <img src="photo\image4.jpeg" alt="Client">
                <p>"The team did an outstanding job on our new home. Highly recommend!"</p>
            </div>
            <div class="testimonial-card">
                <img src="photo\image5.jpg" alt="Client">
                <p>"Professional and timely service. Our office space turned out amazing!"</p>
            </div>
        </section>
    </div>



    <?php include("header/footer.php");?>

</body>
</html>
