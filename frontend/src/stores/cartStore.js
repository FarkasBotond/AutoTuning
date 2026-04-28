import { defineStore } from 'pinia'

const MAX_PER_PRODUCT = 5
const MAX_TOTAL_ITEMS = 10

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [],
        lastError: ''
    }),
    persist: true,
    getters: {
        totalItems: (state) => {
            return state.items.reduce((sum, item) => sum + item.quantity, 0)
        },
        totalPrice: (state) => {
            return state.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
        }
    },

    actions: {
        setError(message) {
            this.lastError = message
            return {
                success: false,
                message
            }
        },

        clearError() {
            this.lastError = ''
        },

        addToCart(product) {
            this.clearError()
            const existingItem = this.items.find(item => item.id === product.id)

            if (this.totalItems >= MAX_TOTAL_ITEMS) {
                return this.setError('A kosárban összesen legfeljebb 10 termék lehet.')
            }

            if (existingItem) {
                if (existingItem.quantity >= MAX_PER_PRODUCT) {
                    return this.setError('Egy termékből legfeljebb 5 darab lehet a kosárban.')
                }

                existingItem.quantity += 1
                return {
                    success: true,
                    message: `${product.name} hozzáadva a kosárhoz`
                }
            }

            this.items.push({
                id: product.id,
                name: product.name,
                brand: product.brand,
                image: product.image,
                price: product.price,
                quantity: 1
            })

            return {
                success: true,
                message: `${product.name} hozzáadva a kosárhoz`
            }
        },

        removeFromCart(productId) {
            this.clearError()
            this.items = this.items.filter(item => item.id !== productId)
        },

        increaseQuantity(productId) {
            this.clearError()
            const item = this.items.find(item => item.id === productId)

            if (!item) {
                return
            }

            if (item.quantity >= MAX_PER_PRODUCT) {
                this.setError('Egy termékből legfeljebb 5 darab lehet a kosárban.')
                return
            }

            if (this.totalItems >= MAX_TOTAL_ITEMS) {
                this.setError('A kosárban összesen legfeljebb 10 termék lehet.')
                return
            }

            item.quantity += 1
        },

        decreaseQuantity(productId) {
            this.clearError()
            const item = this.items.find(item => item.id === productId)

            if (!item) {
                return
            }

            if (item.quantity > 1) {
                item.quantity -= 1
            } else {
                this.removeFromCart(productId)
            }
        },

        clearCart() {
            this.items = []
            this.clearError()
        }
    }
})
