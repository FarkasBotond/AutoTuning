import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: []
    }),
    getters: {
        totalItems: (state) => {
            return state.items.reduce((sum, item) => sum + item.quantity, 0)
        },
        totalPrice: (state) => {
            return state.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
        }
    },

    actions: {
        addToCart(product) {
            const existingItem = this.items.find(item => item.id === product.id)

            if (existingItem) {
                existingItem.quantity += 1
                return
            }

            this.items.push({
                id: product.id,
                name: product.name,
                brand: product.brand,
                image: product.image,
                price: product.price,
                quantity: 1
            })
        },

        removeFromCart(productId) {
            this.items = this.items.filter(item => item.id !== productId)
        },

        increaseQuantity(productId) {
            const item = this.items.find(item => item.id === productId)

            if (item) {
                item.quantity += 1
            }
        },

        decreaseQuantity(productId) {
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
        }
    }
})