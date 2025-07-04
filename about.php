<?php include("header/header1.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .abc{
            color: #000;
        }
    </style>
</head>
<body>
    
    <section class="bg-white text-black text-center py-5">
        <div class="container">
            <h1>Lorem ipsum dolor sit amet.</h1>
            <p class="lead">Building Excellence, Crafting Dreams</p>
        </div>
    </section>
    
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6 abc">
                <h2>Who We Are</h2>
                <p>lorem Builders is a premier construction company with over 20 years of experience in delivering high-quality residential and commercial projects. Our team is dedicated to innovation, sustainability, and client satisfaction.</p>
            </div>
            <div class="col-md-6">
                <img src="photo4.jpg" class="img-fluid rounded" alt="Construction site">
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-md-6">
                <img src="photo/image1.jpg" class="img-fluid rounded" alt="Our mission">
            </div>
            <div class="col-md-6 abc">
                <h2>Our Mission</h2>
                <p>We strive to create durable, aesthetically pleasing, and functional spaces while maintaining the highest industry standards. Our mission is to bring your vision to life with precision and excellence.</p>
            </div>
        </div>
        
        <div class="row mt-5 text-center">
            <h2 class="abc">Why Choose Us?</h2>
            <div class="col-md-3">
                <div class="card shadow-lg p-3">
                    <h5>Experienced Professionals</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-lg p-3">
                    <h5>High-Quality Materials</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-lg p-3">
                    <h5>On-Time Project Completion</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-lg p-3">
                    <h5>Customer-Centric Approach</h5>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="bg-dark text-white text-center py-3">
        <?php include("header/footer.php"); ?>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>