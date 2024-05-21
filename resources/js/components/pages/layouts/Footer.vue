<script setup>

// THIS FUNCTION IS ONLY FOR BACK BUTTON
import { useRouter } from 'vue-router';
const router = useRouter();
const goBack = () => {
    // router.go(-1); // both of them are same
    router.back();
 
  };

</script>

<template>    
    <button class="btn btn-warning btn-xs mr-4" title="Back Button" @click="goBack"><i class="fa fa-backward"></i></button>    

     <!-- To the left -->
     <div class="float-left d-none d-sm-inline">
                هر څه دلته شته!
            </div>
            <!-- Default to the left -->
            <strong>د کاپي حقوق &copy; 2024-2024 <a href="https://www.facebook.com/faizullah.firozi">فیض الله
                    فیروزی</a>.</strong> سره خوندي دي.
</template>