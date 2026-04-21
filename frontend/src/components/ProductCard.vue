<script setup>
defineProps({
    product: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <article>
        <div>
            <img
                :src="product.image"
                :alt="product.name"
            >

            <span v-if="product.badge">
                {{ product.badge }}
            </span>
        </div>

        <div>
            <h3>{{ product.name }}</h3>

            <p>{{ product.brand }}</p>

            <div>
                <span v-if="product.oldPrice">
                    {{ product.oldPrice }} Ft
                </span>

                <span>
                    {{ product.price }} Ft
                </span>
            </div>

            <p>{{ product.stockText }}</p>

            <button type="button">
                Megnézem
            </button>
        </div>
    </article>
</template>