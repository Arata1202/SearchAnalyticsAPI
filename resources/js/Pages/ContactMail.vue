<template>
    <div>
      <h1>Real University Student Emails</h1>
      <ul>
        <li v-for="email in emails" :key="email.id">
          Subject: {{ getEmailSubject(email) }}
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
      const response = await axios.get('/api/emails/real-university-student');
      emails.value = response.data;
    } catch (error) {
      console.error('There was an error!', error);
    }
  });
  
  const getEmailSubject = (email) => {
    return email.payload.headers.find(header => header.name === 'Subject').value;
  };
  </script>
  