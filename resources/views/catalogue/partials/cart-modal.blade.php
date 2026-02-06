<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-shopping-cart me-2"></i>Mon Panier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="cartItems">
                    <!-- Les articles du panier seront ajoutés ici -->
                </div>
                <div id="emptyCart" class="text-center py-4">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Votre panier est vide</h5>
                    <p class="text-muted">Ajoutez des produits pour commencer vos achats</p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Total: <span id="cartTotal" class="text-success">0 FCFA</span></h5>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">
                            Continuer mes achats
                        </button>
                        <button type="button" class="btn btn-success flex-fill" id="proceedToCheckout">
                            <i class="fas fa-credit-card me-1"></i>Passer commande
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>