<script setup>
defineProps({
    show: {
        type: Boolean,
        default : false
    },
    message:{
        type: String,
        default: ''
    }
})
</script>

<template>
    <transition enter-active-class="transition duration-300" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
        <div v-if="show" class="fixed left-1/2 top-8 z-50 w-[min(92vw,620px)] -translate-x-1/2 rounded-2xl border border-emerald-200 bg-emerald-500/95 px-6 py-4 text-center text-sm font-semibold text-white shadow-2xl backdrop-blur-sm md:text-base">
            <span class="inline-flex items-center gap-2">
                <span class="inline-block h-2 w-2 rounded-full bg-white"></span>
                {{ message }}
            </span>
        </div>
    </transition>

</template>