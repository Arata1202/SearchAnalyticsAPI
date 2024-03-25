<template>
  <div>
    <h1>Emails</h1>
    <ul>
      <li v-for="email in emails" :key="email.id">
        ID: {{ email.id }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const emails = ref([]);

onMounted(async () => {
  try {
    const response = await axios.get('/api/emails');
    emails.value = response.data;
  } catch (error) {
    console.error("There was an error fetching the emails: ", error);
  }
});
</script>
