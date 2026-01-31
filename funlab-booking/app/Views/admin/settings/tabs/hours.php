<form action="/admin/settings/save" method="POST">
    <input type="hidden" name="category" value="hours">

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-clock"></i> Horaires de travail</h5>
        </div>
        <div class="card-body">
            <?php 
            $days = [
                'monday' => 'Lundi',
                'tuesday' => 'Mardi',
                'wednesday' => 'Mercredi',
                'thursday' => 'Jeudi',
                'friday' => 'Vendredi',
                'saturday' => 'Samedi',
                'sunday' => 'Dimanche'
            ];
            
            foreach ($days as $key => $label): 
                $dayData = $settings['business_hours_' . $key] ?? '{"open":"09:00","close":"22:00","enabled":true}';
                
                // Décoder le JSON si c'est une chaîne
                if (is_string($dayData)) {
                    $daySettings = json_decode($dayData, true) ?: ['open' => '09:00', 'close' => '22:00', 'enabled' => true];
                } else {
                    $daySettings = $dayData;
                }
            ?>
                <div class="row mb-3 align-items-center">
                    <div class="col-md-2">
                        <strong><?= $label ?></strong>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Ouverture</label>
                        <input type="time" class="form-control" 
                            name="settings[business_hours_<?= $key ?>][open]" 
                            value="<?= esc($daySettings['open']) ?>"
                            <?= !$daySettings['enabled'] ? 'disabled' : '' ?>>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Fermeture</label>
                        <input type="time" class="form-control" 
                            name="settings[business_hours_<?= $key ?>][close]" 
                            value="<?= esc($daySettings['close']) ?>"
                            <?= !$daySettings['enabled'] ? 'disabled' : '' ?>>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" 
                                name="settings[business_hours_<?= $key ?>][enabled]" 
                                value="1" 
                                <?= $daySettings['enabled'] ? 'checked' : '' ?>>
                            <label class="form-check-label">Ouvert</label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Sauvegarder
            </button>
        </div>
    </div>
</form>
