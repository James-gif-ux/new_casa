<?php 
    include 'nav/header.php'; 
    require_once '../model/server.php';
    $connector = new Connector();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql = "INSERT INTO messages (message_contact_id, recipient_email, subject, message_content, date_sent, status) 
                VALUES (?, ?, ?, ?, NOW(), 'unread')";
        $stmt = $connector->getConnection()->prepare($sql);
        try {
            $stmt->execute([
                1, // message_contact_id
                $_POST['email'], // recipient_email
                $_POST['subject'], // subject
                $_POST['message'] // message_content
            ]);
            echo "<script>alert('Please check your email for an update');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Failed to send message. Please try again later.');</script>";
        }
    }
?>

    <section style="padding: clamp(4rem, 8vw, 10rem) 1rem;">
        <div class="row">
            <div class="col-lg-12">
                <div class="sec-title text-center mb-5">
                    <p class="sec-sub-title mb-3">Contact Us</p>
                    <h2 class="h2-title">For more inquiries</h2>
                    <div class="sec-title-shape mb-4">
                        <img src="../assets/images/title-shape.svg" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-grid" style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr)); gap: 2rem; position: relative;">
            <!-- Contact Info Side -->
            <div style="background: white; padding: clamp(1rem, 3vw, 2rem); border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; position: relative; z-index: 1;"
                onmouseover="this.style.transform='translateY(-5px)'" 
                onmouseout="this.style.transform='translateY(0)'">
                <h3 style="color:rgb(192, 126, 94); font-size: clamp(1.4rem, 2.5vw, 1.8rem); margin-bottom: 2rem; font-family: 'impact'; border-bottom: 2px solid rgb(192, 126, 94); padding-bottom: 1rem;">Find Us</h3>
                
                <!-- Location -->
                <div style="margin-bottom: 2rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <i class="fas fa-map-marker-alt" style="color:rgb(192, 126, 94); font-size: clamp(1.2rem, 2vw, 1.5rem); margin-right: 1rem;"></i>
                        <h4 style="color: rgb(192, 126, 94); font-size: clamp(1rem, 1.5vw, 1.2rem);">Address</h4>
                    </div>
                    <p style="color: #666; line-height: 1.6; padding-left: 2.5rem; font-size: clamp(0.9rem, 1.2vw, 1rem);"><?=$info['contact_address']?></p>
                </div>

                <!-- Phone -->
                <div style="margin-bottom: 2rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <i class="fas fa-phone" style="color: rgb(192, 126, 94); font-size: clamp(1.2rem, 2vw, 1.5rem); margin-right: 1rem;"></i>
                        <h4 style="color: rgb(192, 126, 94); font-size: clamp(1rem, 1.5vw, 1.2rem);">Phone</h4>
                    </div>
                    <p style="color: #666; line-height: 1.6; padding-left: 2.5rem; font-size: clamp(0.9rem, 1.2vw, 1rem);"><?=$info['contact_number']?></p>
                </div>

                <!-- Email -->
                <div style="margin-bottom: 2rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <i class="fas fa-envelope" style="color: rgb(192, 126, 94); font-size: clamp(1.2rem, 2vw, 1.5rem); margin-right: 1rem;"></i>
                        <h4 style="color:rgb(192, 126, 94); font-size: clamp(1rem, 1.5vw, 1.2rem);">Email</h4>
                    </div>
                    <p style="color: #666; line-height: 1.6; padding-left: 2.5rem; font-size: clamp(0.9rem, 1.2vw, 1rem);"><?=$info['contact_email']?></p>
                </div>
            </div>

            <!-- Contact Form Side -->
            <div style="background: white; padding: clamp(1.5rem, 4vw, 3rem); border-radius: 15px; box-shadow: 0 10px 30px rgba(192, 38, 38, 0.1); position: relative; z-index: 1;">
                <form method="POST" action="contact.php" style="display: grid; gap: 1.5rem;">
                    <div class="input-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 200px), 1fr)); gap: 1.5rem;">
                        <div style="position: relative;">
                            <i class="fas fa-user" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color:rgb(192, 126, 94);"></i>
                            <input type="text" name="name" placeholder="Your Name" required 
                                style="padding: 1rem 1rem 1rem 3rem; border: 2px solid rgb(192, 126, 94); border-radius: 8px; font-size: clamp(0.9rem, 1.2vw, 1rem); width: 100%;">
                        </div>
                        <div style="position: relative;">
                            <i class="fas fa-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color:rgb(192, 126, 94);"></i>
                            <input type="email" name="email" placeholder="Your Email" required 
                                style="padding: 1rem 1rem 1rem 3rem; border: 2px solid rgb(192, 126, 94); border-radius: 8px; font-size: clamp(0.9rem, 1.2vw, 1rem); width: 100%;">
                        </div>
                    </div>
                    <div style="position: relative;">
                        <i class="fas fa-heading" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color:rgb(192, 126, 94);"></i>
                        <input type="text" name="subject" placeholder="Subject" required 
                            style="padding: 1rem 1rem 1rem 3rem; border: 2px solid rgb(192, 126, 94); border-radius: 8px; font-size: clamp(0.9rem, 1.2vw, 1rem); width: 100%;">
                    </div>
                    <div style="position: relative;">
                        <i class="fas fa-comment" style="position: absolute; left: 1rem; top: 1.2rem; color:rgb(192, 126, 94);"></i>
                        <textarea name="message" placeholder="Your Message" required 
                            style="padding: 1rem 1rem 1rem 3rem; border: 2px solid rgb(192, 126, 94); border-radius: 8px; font-size: clamp(0.9rem, 1.2vw, 1rem); min-height: 150px; resize: vertical; width: 100%;"></textarea>
                    </div>
                    <button type="submit" 
                        style="padding: clamp(0.8rem, 1.5vw, 1rem) clamp(1.5rem, 2.5vw, 2rem); background: linear-gradient(45deg,rgb(119, 77, 56), #ff8243); 
                            color: white; border: none; border-radius: 8px; font-size: clamp(1rem, 1.3vw, 1.1rem); cursor: pointer; 
                            transition: all 0.3s ease; position: relative; overflow: hidden;">
                        <i class="fas fa-paper-plane" style="margin-right: 0.5rem;"></i>
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>

<?php include 'nav/footer.php'; ?>