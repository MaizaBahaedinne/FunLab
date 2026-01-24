<form action="/admin/settings/save" method="POST">
    <input type="hidden" name="category" value="mail_template">

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-file-earmark-text"></i> Templates Email</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Variables disponibles:</strong><br>
                <code>{name}</code> - Nom du client<br>
                <code>{email}</code> - Email du client<br>
                <code>{booking_date}</code> - Date de réservation<br>
                <code>{booking_time}</code> - Heure de réservation<br>
                <code>{game_name}</code> - Nom du jeu<br>
                <code>{room_name}</code> - Nom de la salle<br>
                <code>{num_players}</code> - Nombre de joueurs<br>
                <code>{total_price}</code> - Prix total<br>
                <code>{confirmation_code}</code> - Code de confirmation
            </div>

            <div class="mb-4">
                <label class="form-label"><strong>Confirmation de réservation</strong></label>
                <textarea class="form-control" name="settings[mail_template_booking_confirmation]" 
                    rows="8"><?= esc($settings['mail_template_booking_confirmation'] ?? '') ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label"><strong>Rappel de réservation</strong></label>
                <textarea class="form-control" name="settings[mail_template_booking_reminder]" 
                    rows="8"><?= esc($settings['mail_template_booking_reminder'] ?? '') ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label"><strong>Annulation de réservation</strong></label>
                <textarea class="form-control" name="settings[mail_template_booking_cancellation]" 
                    rows="8"><?= esc($settings['mail_template_booking_cancellation'] ?? '') ?></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Sauvegarder
            </button>
        </div>
    </div>
</form>
