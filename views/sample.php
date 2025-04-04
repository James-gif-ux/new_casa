<div class="container">
    <form action="validate_room.php" method="POST" class="room-validation-form">
        <h2>Room Validation Form</h2>
        
        <div class="form-group">
            <label for="room_number">Room Number:</label>
            <input type="text" id="room_number" name="room_number" required>
        </div>

        <div class="form-group">
            <label for="room_type">Room Type:</label>
            <select id="room_type" name="room_type" required>
                <option value="">Select Room Type</option>
                <option value="single">Single</option>
                <option value="double">Double</option>
                <option value="suite">Suite</option>
            </select>
        </div>

        <div class="form-group">
            <label for="max_occupancy">Maximum Occupancy:</label>
            <input type="number" id="max_occupancy" name="max_occupancy" min="1" required>
        </div>

        <div class="form-group">
            <label for="room_status">Room Status:</label>
            <select id="room_status" name="room_status" required>
                <option value="">Select Status</option>
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>

        <button type="submit" class="btn">Validate Room</button>
    </form>
</div>

<style>
.container {
    max-width: 500px;
    margin: 20px auto;
    padding: 20px;
}

.room-validation-form {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 5px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.btn {
    background: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn:hover {
    background: #45a049;
}
</style>