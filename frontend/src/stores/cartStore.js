import { defineStore } from 'pinia'

const CART_STORAGE_KEY = 'race_district_cart'

const loadCartItems = () => {
    try {
        const savedCart = localStorage.getItem(CART_STORAGE_KEY)

        if (!savedCart) {
            return []
        }

        const parsedCart = JSON.parse(savedCart)

        if (!Array.isArray(parsedCart)) {
            return []
        }

        return parsedCart
    } catch (error) {
        console.error('Failed to load cart from localStorage:', error)
        return []
    }
}

const saveCartItems = (items) => {
    try {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(items))
    } catch (error) {
        console.error('Failed to save cart to localStorage:', error)
    }
}

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: loadCartItems()
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
        saveCart() {
            saveCartItems(this.items)
        },

        addToCart(product) {
            const existingItem = this.items.find(item => item.id === product.id)

            if (existingItem) {
                existingItem.quantity += 1
                this.saveCart()
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

            this.saveCart()
        },

        removeFromCart(productId) {
            this.items = this.items.filter(item => item.id !== productId)
            this.saveCart()
        },

        increaseQuantity(productId) {
            const item = this.items.find(item => item.id === productId)

            if (!item) {
                return
            }

            item.quantity += 1
            this.saveCart()
        },

        decreaseQuantity(productId) {
            const item = this.items.find(item => item.id === productId)

            if (!item) {
                return
            }

            if (item.quantity > 1) {
                item.quantity -= 1
            } else {
                this.items = this.items.filter(item => item.id !== productId)
            }

            this.saveCart()
        },

        clearCart() {
            this.items = []
            this.saveCart()
        }
    }
})