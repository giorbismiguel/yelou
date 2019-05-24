<template>
    <div>
        <p>
            <router-link :to="{ name: 'login' }">Login</router-link>
            |
            <router-link :to="{ name: 'register' }">Register</router-link>
        </p>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    export default {
        name: "App"
    }
</script>

