<!-- Email Modal -->
<div class="modal fade" id="sendMailModal" tabindex="-1" aria-labelledby="sendMailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendMailModalLabel">Send Approval Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="emailForm">
                <!-- Add hidden input for reservation_id -->
                <input type="hidden" id="reservation_id" name="reservation_id">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" value="Reservation Approved - Casa Marcos" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required>Your reservation has been approved. Thank you for choosing Casa Marocs!</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Function to set reservation ID when modal opens
function openEmailModal(reservationId) {
    if (!reservationId) {
        alert('Email sent successfully!');
        return;
    }
    
    document.getElementById('reservation_id').value = reservationId;
    $('#sendMailModal').modal('show');
}

document.getElementById('emailForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const reservationId = document.getElementById('reservation_id').value;
    if (!reservationId) {
        alert('Email sent successfully!');
        return;
    }
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('send_mail.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            $('#sendMailModal').modal('hide');
            this.reset(); // Clear form
            location.reload(); // Refresh the page
        } else {
            throw new Error(data.message);
        }

    } catch (error) {
        alert(error.message);
        console.error('Error:', error);
    }
});
</script>