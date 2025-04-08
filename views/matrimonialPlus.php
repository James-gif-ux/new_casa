    <?php
        include_once 'nav/header.php';
    ?>

        <div class="hero-section" style="position: relative;">
            <img src="../images/mp.jpg" alt="Barkada Room" style="width: 100%; height: 600px; object-fit: cover;">
        </div>

        <div style="max-width: 1500px; margin: -30px auto 50px; padding: 2rem 1rem; position: relative; background-color: #2c3e50; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <div style="text-align: center; margin-bottom: 4rem; padding-bottom: 2rem; border-bottom: 2px solid #f0e6dd;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap;">
                    <h2 style="color: rgb(218, 191, 156); font-size: 2.8rem; font-family: 'impact'; margin-top:10px; text-transform: uppercase; letter-spacing: 1px;">
                        Matrimonial Plus
                    </h2>
                    <div style="background: rgb(218, 191, 156); padding: 0.8rem 1.5rem; border-radius: 12px; margin-top: 10px;">
                        <p style="color: white; margin: 0;"><span style="font-size: 2rem; font-weight: bold;">₱5,399</span></p>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-bottom: 4rem; max-width: 1500px; margin-left: auto; margin-right: auto;">
                <div style="text-align: center; padding: 3rem; background: #f9f6f2; border-radius: 15px; transition: transform 0.3s; cursor: pointer; border: 1px solid #e6d5c5;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-bed" style="font-size: 2.5rem; color: rgb(102, 67, 35); margin-bottom: 1.5rem;"></i>
                    <h3 style="color: rgb(102, 67, 35); font-size: 1.8rem; font-family: 'impact'; margin-bottom: 0.8rem;">Bed</h3>
                    <p style="color: #666; font-size: 1.2rem;">Queen-size Beds</p>
                </div>
                <div style="text-align: center; padding: 2.5rem; background: #f9f6f2; border-radius: 15px; transition: transform 0.3s; cursor: pointer; border: 1px solid #e6d5c5;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-users" style="font-size: 2.5rem; color: rgb(102, 67, 35); margin-bottom: 1.5rem;"></i>
                    <h3 style="color: rgb(102, 67, 35); font-size: 1.8rem; font-family: 'impact'; margin-bottom: 0.8rem;">Capacity</h3>
                    <p style="color: #666; font-size: 1.2rem;">2 Person</p>
                </div>
            </div>

            <div style="background: #f9f6f2; padding: 3rem; border-radius: 20px; border: 1px solid #e6d5c5;">
                <h3 style="color: rgb(102, 67, 35); font-size: 2rem; font-family: 'impact'; margin-bottom: 2rem; text-align: center;">Room Features</h3>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 10px;">
                        <i class="fas fa-check-circle" style="color: rgb(102, 67, 35); font-size: 1.2rem;"></i>
                        <span style="color: #666;">Larger private bathroom</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 10px;">
                        <i class="fas fa-check-circle" style="color: rgb(102, 67, 35); font-size: 1.2rem;"></i>
                        <span style="color: #666;">TV & Wifi in Rooms</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 10px;">
                        <i class="fas fa-check-circle" style="color: rgb(102, 67, 35); font-size: 1.2rem;"></i>
                        <span style="color: #666;">Larger room</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 10px;">
                        <i class="fas fa-check-circle" style="color: rgb(102, 67, 35); font-size: 1.2rem;"></i>
                        <span style="color: #666;">Free use of swimmming pool</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 10px;">
                        <i class="fas fa-check-circle" style="color: rgb(102, 67, 35); font-size: 1.2rem;"></i>
                        <span style="color: #666;">Breakfast Included for 2</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 10px;">
                        <i class="fas fa-check-circle" style="color: rgb(102, 67, 35); font-size: 1.2rem;"></i>
                        <span style="color: #666;">Air conditioning</span>
                    </div>
                </div>
            </div>
        </div>


<div style="max-width: 1500px; margin: 40px auto; padding: 30px; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
    <!-- Calendar Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0;">
        <button style="font-size: 18px; color: #664323; background: none; border: none; cursor: pointer; transition: 0.3s;">&lt; Previous</button>
        <h2 style="font-size: 24px; color: #2c3e50; font-weight: 600;">Booking Calendar</h2>
        <button style="font-size: 18px; color: #664323; background: none; border: none; cursor: pointer; transition: 0.3s;">Next &gt;</button>
    </div>

    <!-- Calendar Grid -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; margin-bottom: 40px;">
        <!-- Current Month -->
        <div style="background: #f9f6f2; padding: 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="text-align: center; color: #664323; font-size: 20px; margin-bottom: 15px; font-weight: bold;">April 2024</h3>
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; text-align: center;">
                <div style="font-weight: 600; color: #664323; padding: 8px;">Sun</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Mon</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Tue</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Wed</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Thu</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Fri</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Sat</div>
                
                <!-- Calendar days with hover effect -->
                <?php
                    for($i = 1; $i <= 30; $i++) {
                        echo "<div style='padding: 8px; cursor: pointer; border-radius: 50%; transition: 0.3s;' 
                              onmouseover=\"this.style.background='#dabf9c'; this.style.color='white';\" 
                              onmouseout=\"this.style.background='transparent'; this.style.color='#666';\">{$i}</div>";
                    }
                ?>
            </div>
        </div>

        <!-- Next Month -->
        <div style="background: #f9f6f2; padding: 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="text-align: center; color: #664323; font-size: 20px; margin-bottom: 15px; font-weight: bold;">May 2024</h3>
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; text-align: center;">
                <!-- Days header -->
                <div style="font-weight: 600; color: #664323; padding: 8px;">Sun</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Mon</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Tue</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Wed</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Thu</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Fri</div>
                <div style="font-weight: 600; color: #664323; padding: 8px;">Sat</div>
                
                <!-- Calendar days with hover effect -->
                <?php
                    for($i = 1; $i <= 31; $i++) {
                        echo "<div style='padding: 8px; cursor: pointer; border-radius: 50%; transition: 0.3s;' 
                              onmouseover=\"this.style.background='#dabf9c'; this.style.color='white';\" 
                              onmouseout=\"this.style.background='transparent'; this.style.color='#666';\">{$i}</div>";
                    }
                ?>
            </div>
        </div>
    </div>

    <!-- Booking Form -->
    <div style="background: #f9f6f2; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h4 style="color: #664323; font-size: 22px; margin-bottom: 20px; text-align: center; font-weight: bold;">Make Your Reservation</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div>
                <label style="display: block; color: #664323; margin-bottom: 8px; font-weight: 500;">Check-in Date *</label>
                <input type="date" style="width: 100%; padding: 12px; border: 2px solid #dabf9c; border-radius: 10px; font-size: 16px; outline: none; transition: 0.3s;" 
                       onmouseover="this.style.borderColor='#664323'" 
                       onmouseout="this.style.borderColor='#dabf9c'">
            </div>
            <div>
                <label style="display: block; color: #664323; margin-bottom: 8px; font-weight: 500;">Check-out Date *</label>
                <input type="date" style="width: 100%; padding: 12px; border: 2px solid #dabf9c; border-radius: 10px; font-size: 16px; outline: none; transition: 0.3s;"
                       onmouseover="this.style.borderColor='#664323'" 
                       onmouseout="this.style.borderColor='#dabf9c'">
            </div>
        </div>
        <button style="width: 100%; margin-top: 20px; padding: 15px; background: #664323; color: white; border: none; border-radius: 10px; font-size: 18px; cursor: pointer; transition: 0.3s;"
                onmouseover="this.style.background='#dabf9c'" 
                onmouseout="this.style.background='#664323'">
            Check Availability
        </button>
    </div>
</div>

        

        <?php include 'nav/footer.php'; ?>