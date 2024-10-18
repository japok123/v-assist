<template>
    <v-card class="w-100 d-flex justify-content-center align-items-center background" elevation="0">
        <router-view class="w-100"/>
    </v-card>
</template>

<script>
export default {
}
</script>       
<style lang="scss" scoped>
    .background{
        background: linear-gradient(to bottom, #ff7e5f, #feb47b);
        height: 100vh;
    }
</style>
