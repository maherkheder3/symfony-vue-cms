<template>
    <div :style="style">
        <v-progress-linear color="black" :indeterminate="true"></v-progress-linear>
    </div>
</template>

<script>
    export default {
        name:"loading",
        data (){
            return {
                style:{
                   position: 'fixed',
                   top: '-14px',
                   left: '0',
                   width: '100%',
                   zIndex:"10000000"
                }
            };
        }
    }
</script>

