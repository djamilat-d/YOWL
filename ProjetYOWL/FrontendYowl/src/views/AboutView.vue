<template>
  <div class="about">
    <h1>This is an about page</h1>
  </div>
</template>

<style>
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}
</style>
<script>
export default {
  created(){
    fetch('http://127.0.0.1:8000/api/test')
    .then(function (response){
      console.log(response)
    })

    console.log('Guy');
  }
}
</script>