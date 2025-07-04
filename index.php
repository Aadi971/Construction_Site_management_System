<?php include("header/header1.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: url("photo3.jpg") no-repeat center center/cover;
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .container {
            flex: 1;
            text-align: center;
            padding: 60px 20px;
        }

        
        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            z-index: 100;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        .nav-links {
            list-style: none;
            display: flex;
        }

        .nav-links li {
            margin: 0 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            transition: 0.3s ease-in-out;
        }

        .nav-links a:hover {
            color: #1e90ff;
        }

        .btn {
            padding: 10px 20px;
            background: #1e90ff;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            transition: 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
        }

        
        .menu-toggle {
            display: none;
            font-size: 28px;
            cursor: pointer;
            color: white;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 60px;
                right: 0;
                background: rgba(0, 0, 0, 0.9);
                width: 100%;
                text-align: center;
                padding: 20px 0;
            }

            .nav-links.active {
                display: flex;
            }

            .nav-links li {
                margin: 10px 0;
            }

            .menu-toggle {
                display: block;
            }
        }

        
        .hero {
            text-align: center;
            padding: 100px 20px;
        }

        .hero h1 {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        
        .services {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            padding: 80px 20px;
            text-align: center;
        }

        .service-box {
            width: 300px;
            background: rgba(255, 255, 255, 0.5);
            padding: 20px;
            margin: 15px;
            border-radius: 10px;
            transition: 0.3s ease;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            /* transition: 0.3s ease; */
        }

        .service-box:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .service-box i {
            font-size: 40px;
            margin-bottom: 10px;
            color: #1e90ff;
        }

        .service-box h3 {
            color: #000;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .service-box p{
            color: #000;
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
            color: #000;
        }

        
        .footer {
            background: rgba(0, 0, 0, 0.8);
            text-align: center;
            padding: 20px;
            margin-top: auto;
        }

    </style>
</head>
<body>
    

    <div class="container">
        <section class="hero">
            <h1>Welcome to MyWebsite</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit..</p>
            <a href="services.php" class="btn">Explore Services</a>
        </section>

        <section class="services">
            <div class="service-box">
                <i class="fas fa-laptop"></i>
                <h3>lorem</h3>
                <p>Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</p>
            </div>
            <div class="service-box">
                <i class="fas fa-chart-line"></i>
                <h3>lorem</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem, neque..</p>
            </div>
            <div class="service-box">
                <i class="fas fa-cogs"></i>
                <h3>lorem</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque, id!.</p>
            </div>
        </section>

        <section class="testimonials">
            <h2>What Our Clients Say</h2>
            <div class="testimonial-card">
                <img src="photo-2.jpg" alt="User">
                <p>"Lorem ipsum dolor, sit amet consectetur adipisicing elit. Mollitia, odit?."</p>
            </div>
            <div class="testimonial-card">
                <img src="photo3.jpg" alt="User">
                <p>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima, laborum?."</p>
            </div>
        </section>
    </div>

    <?php
    include("header/footer.php");
    ?>

</body>
</html>
