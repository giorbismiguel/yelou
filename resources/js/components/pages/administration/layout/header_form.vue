<template>
    <h3 class="mt-3 mb-4">
        <slot></slot>
    </h3>
</template>

<script>
    export default {
        name: "HeaderForm"
    }
</script>

<style scoped>
    h3 {
        font-family: 'Montserrat-Bold', sans-serif;
        font-size: 24px;
        color: #2089C0;
    }
</style>