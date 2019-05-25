<template>
    <div>
        <navbar></navbar>

        <div class="container">
            <router-view></router-view>
        </div>

        <feet></feet>
    </div>
</template>

<script>
    export default {
        name: "App"
    }
</script>

