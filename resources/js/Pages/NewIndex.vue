<template>
    <div>
      <h1>URLインデックス登録</h1>
      <input v-model="url" placeholder="インデックス登録するURLを入力" />
      <button @click="submitUrl">インデックス登録をリクエスト</button>
    </div>
  </template>
<script setup>
import { ref } from 'vue';
import axios from 'axios';

const url = ref('');

const submitUrl = async () => {
  try {
    const response = await axios.post('/api/search-console/index', { url: url.value });
    alert('リクエストが成功しました。');
  } catch (error) {
    console.error('Error:', error);
    alert('リクエスト中にエラーが発生しました。');
  }
};
</script>
  