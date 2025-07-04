<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Elite Builders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .contact-section {
            background-color: #f8f9fa;
            /* background-color: inherit; */
            padding: 60px 0;
        }
        .contact-card {
            background: white;
            color: black;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #007BFF;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
        <?php include("header1.php"); ?>
        
    <section class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1>Contact Lorem Builders</h1>
            <p class="lead">Get in touch with us for your construction needs</p>
        </div>
    </section>
    
    <div class="container contact-section py-5">
        <div class="row">
            <div class="col-md-6">
                <div class="contact-card">
                    <h2>Contact Information</h2>
                    <p><strong>Address:</strong> 123 Main Street, Delhi, India</p>
                    <p><strong>Phone:</strong> +91 923 456 7890</p>
                    <p><strong>Email:</strong> contact@gmail.com</p>
                    <p><strong>Business Hours:</strong> Mon - Fri: 9:00 AM - 6:00 PM</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="contact-card">
                    <h2>Send Us a Message</h2>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Your Name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Your Email">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="4" placeholder="Your Message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-custom">Send Message</button>
                    </form>
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
