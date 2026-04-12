<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="shadow rounded p-4 mb-4 bg-white">
          <h3 class="text-center mb-4 text-primary">Créer un compte</h3>
          <form @submit.prevent="addPost">
            <div class="mb-3">
              <label for="nom" class="form-label">Nom :</label>
              <input type="text" v-model="newPost.nom" id="nom" class="form-control" required />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email :</label>
              <input
                type="email"
                v-model="newPost.email"
                id="email"
                class="form-control"
                required
              />
            </div>
            <div class="mb-3">
              <label for="birthday" class="form-label">Birthday :</label>
              <input v-model="newPost.birth_year" id="birthday" class="form-control" required />
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone :</label>
              <input v-model="newPost.phone" id="phone" class="form-control" required />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password :</label>
              <input
                type="password"
                v-model="newPost.password"
                id="password"
                class="form-control"
                required
              />
            </div>
            <div class="mb-3">
              <label for="password_conf" class="form-label">Password_confirmation :</label>
              <input
                type="password"
                v-model="newPost.password_confirmation"
                id="password_conf"
                class="form-control"
                required
              />
            </div>
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
import {ref} from 'vue';
const newPost = ref({
  nom: '',
  email: '',
  birth_year: '',
  phone: '',
  password: '',
  password_confirmation: '',
});

function addPost(e) {
  e.preventDefault()

  axios
    .post('http://127.0.0.1:8000/api/signup', newPost.value)
    .then((response) => {
      alert('Utilisateur enregistré avec succès');
      console.log(response);
    })
    .catch((error) => {
      console.error("Erreur d'ajout du nouveau post", error)
    })
}
</script>

<style scoped></style>
