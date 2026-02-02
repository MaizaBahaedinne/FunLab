<div class="admin-main">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form id="promoForm" method="post" action="<?= isset($promoCode) ? base_url('admin/promo-codes/update/' . $promoCode['id']) : base_url('admin/promo-codes/store') ?>">
                        <div class="row g-3">
                            <!-- Code -->
                            <div class="col-md-6">
                                <label for="code" class="form-label">Code Promo <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control text-uppercase" 
                                       id="code" 
                                       name="code" 
                                       value="<?= isset($promoCode) ? esc($promoCode['code']) : '' ?>"
                                       placeholder="SUMMER2024"
                                       required>
                                <small class="text-muted">Lettres, chiffres et tirets uniquement</small>
                            </div>

                            <!-- Active Status -->
                            <div class="col-md-6">
                                <label class="form-label">Statut</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           <?= isset($promoCode) && $promoCode['is_active'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_active">
                                        Code actif
                                    </label>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="2"
                                          placeholder="Description du code promo"><?= isset($promoCode) ? esc($promoCode['description']) : '' ?></textarea>
                            </div>

                            <div class="col-12"><hr class="my-2"></div>

                            <!-- Discount Type -->
                            <div class="col-md-6">
                                <label for="discount_type" class="form-label">Type de Réduction <span class="text-danger">*</span></label>
                                <select class="form-select" id="discount_type" name="discount_type" required>
                                    <option value="percentage" <?= isset($promoCode) && $promoCode['discount_type'] === 'percentage' ? 'selected' : '' ?>>
                                        Pourcentage (%)
                                    </option>
                                    <option value="fixed" <?= isset($promoCode) && $promoCode['discount_type'] === 'fixed' ? 'selected' : '' ?>>
                                        Montant Fixe (MAD)
                                    </option>
                                </select>
                            </div>

                            <!-- Discount Value -->
                            <div class="col-md-6">
                                <label for="discount_value" class="form-label">Valeur <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control" 
                                           id="discount_value" 
                                           name="discount_value" 
                                           value="<?= isset($promoCode) ? $promoCode['discount_value'] : '' ?>"
                                           step="0.01"
                                           min="0"
                                           placeholder="10"
                                           required>
                                    <span class="input-group-text" id="discount_unit">%</span>
                                </div>
                            </div>

                            <!-- Min Amount -->
                            <div class="col-md-6">
                                <label for="min_amount" class="form-label">Montant Minimum</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control" 
                                           id="min_amount" 
                                           name="min_amount" 
                                           value="<?= isset($promoCode) ? $promoCode['min_amount'] : '' ?>"
                                           step="0.01"
                                           min="0"
                                           placeholder="100">
                                    <span class="input-group-text">MAD</span>
                                </div>
                                <small class="text-muted">Montant minimum requis (optionnel)</small>
                            </div>

                            <!-- Max Discount (only for percentage) -->
                            <div class="col-md-6" id="max_discount_group">
                                <label for="max_discount" class="form-label">Réduction Maximum</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control" 
                                           id="max_discount" 
                                           name="max_discount" 
                                           value="<?= isset($promoCode) ? $promoCode['max_discount'] : '' ?>"
                                           step="0.01"
                                           min="0"
                                           placeholder="50">
                                    <span class="input-group-text">MAD</span>
                                </div>
                                <small class="text-muted">Plafond de réduction (optionnel)</small>
                            </div>

                            <div class="col-12"><hr class="my-2"></div>

                            <!-- Valid From -->
                            <div class="col-md-6">
                                <label for="valid_from" class="form-label">Valide à partir du</label>
                                <input type="datetime-local" 
                                       class="form-control" 
                                       id="valid_from" 
                                       name="valid_from" 
                                       value="<?= isset($promoCode) && $promoCode['valid_from'] ? date('Y-m-d\TH:i', strtotime($promoCode['valid_from'])) : '' ?>">
                            </div>

                            <!-- Valid Until -->
                            <div class="col-md-6">
                                <label for="valid_until" class="form-label">Valide jusqu'au</label>
                                <input type="datetime-local" 
                                       class="form-control" 
                                       id="valid_until" 
                                       name="valid_until" 
                                       value="<?= isset($promoCode) && $promoCode['valid_until'] ? date('Y-m-d\TH:i', strtotime($promoCode['valid_until'])) : '' ?>">
                            </div>

                            <!-- Usage Limit -->
                            <div class="col-md-6">
                                <label for="usage_limit" class="form-label">Limite d'Utilisation</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="usage_limit" 
                                       name="usage_limit" 
                                       value="<?= isset($promoCode) ? $promoCode['usage_limit'] : '' ?>"
                                       min="1"
                                       placeholder="Illimité">
                                <small class="text-muted">Nombre max d'utilisations (optionnel)</small>
                            </div>

                            <?php if (isset($promoCode)): ?>
                            <!-- Usage Count (read only) -->
                            <div class="col-md-6">
                                <label class="form-label">Utilisé</label>
                                <input type="text" 
                                       class="form-control" 
                                       value="<?= $promoCode['usage_count'] ?> fois"
                                       readonly>
                            </div>
                            <?php endif; ?>

                            <div class="col-12"><hr class="my-2"></div>

                            <!-- Applicable Games -->
                            <div class="col-12">
                                <label class="form-label">Jeux Applicables</label>
                                <p class="text-muted small mb-2">Laisser vide pour appliquer à tous les jeux</p>
                                <div class="row g-2">
                                    <?php 
                                    $applicableGames = isset($promoCode) && $promoCode['applicable_games'] ? json_decode($promoCode['applicable_games'], true) : [];
                                    foreach ($games as $game): 
                                    ?>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="applicable_games[]" 
                                                       value="<?= $game['id'] ?>"
                                                       id="game_<?= $game['id'] ?>"
                                                       <?= in_array($game['id'], $applicableGames) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="game_<?= $game['id'] ?>">
                                                    <?= esc($game['name']) ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg"></i>
                                        <?= isset($promoCode) ? 'Modifier' : 'Créer' ?>
                                    </button>
                                    <a href="<?= base_url('admin/promo-codes') ?>" class="btn btn-secondary">
                                        <i class="bi bi-x-lg"></i> Annuler
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update discount unit based on type
document.getElementById('discount_type').addEventListener('change', function() {
    const unit = document.getElementById('discount_unit');
    const maxDiscountGroup = document.getElementById('max_discount_group');
    
    if (this.value === 'percentage') {
        unit.textContent = '%';
        maxDiscountGroup.style.display = '';
    } else {
        unit.textContent = 'MAD';
        maxDiscountGroup.style.display = 'none';
    }
});

// Trigger on page load
document.getElementById('discount_type').dispatchEvent(new Event('change'));

// Auto-uppercase code
document.getElementById('code').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});

// Form submission
document.getElementById('promoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = this.action;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = '<?= base_url('admin/promo-codes') ?>';
            });
        } else {
            let errorMsg = data.message;
            if (data.errors) {
                errorMsg += '<br><ul class="text-start mt-2">';
                for (let field in data.errors) {
                    errorMsg += '<li>' + data.errors[field] + '</li>';
                }
                errorMsg += '</ul>';
            }
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                html: errorMsg
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Une erreur est survenue'
        });
    });
});
</script>
