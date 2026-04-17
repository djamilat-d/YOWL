<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="shadow rounded p-4 mb-4 bg-white">
          <h3 class="text-center mb-4 text-primary">Créer un compte</h3>
          <form @submit.prevent="addPost">
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary">Poster</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';

function addPost(e) {
  e.preventDefault()

const token = localStorage.getItem('token');

// Interception and configuration of the request before it's sent
axios.interceptors.request.use(
        config => {
            config.headers.authorization = `Bearer ${token}`;
            return config;
        },
        error => { 
//Do something with request error
return Promise.reject(error);
        }
    );

  
axios
    .get('http://127.0.0.1:8000/api/modifier/2')
    .then(function (response) {
      console.log(response);
    })
    .catch((error) => {
      console.error("Erreur d'ajout commentaire", error)
    })
}
</script>

<style scoped></style>
