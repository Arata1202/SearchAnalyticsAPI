<template>
    <MainLayout currentId="Sitemap">
      <div class="px-4 sm:px-6 lg:px-8">
        <div>
        <div class="sm:hidden">
          <label for="tabs" class="sr-only">Select a tab</label>
          <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
            <option v-for="tab in tabs" :key="tab.name" :selected="tab.current">{{ tab.name }}</option>
          </select>
        </div>
      <div class="hidden sm:block">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a v-for="tab in tabs" :key="tab.name" :href="tab.href" :class="[tab.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']" :aria-current="tab.current ? 'page' : undefined">{{ tab.name }}</a>
          </nav>
        </div>
      </div>
    </div>
    <div class="sm:flex sm:items-center" style="margin-top: 30px;">
        <div class="sm:flex-auto">
          <h1 class="text-base font-semibold leading-6 text-gray-900" style="font-size: 30px;">Sitemap</h1>
        </div>
      </div>
      <form @submit.prevent="submitSitemap">
        <div class="mt-6 flex max-w-md gap-x-4">
            <input v-model="sitemapPath" type="text" placeholder="サイトマップのパスを入力" name="sitemap" id="sitemap" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
            <button type="submit" class="flex-none rounded-md bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">送信</button>
        </div>
      </form>
    </div>
  </MainLayout>
  </template>
  
  <script setup>
  import MainLayout from '../Layouts/MainLayout.vue';
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  
  const tabs = [
    { name: 'Sitemap', href: 'sitemap', current: false, id: "Sitemap" },
    { name: 'Regist', href: 'newsitemap', current: true, id: "NewSitemap" },
  ]

  const sitemapPath = ref('');
  
  const submitSitemap = async () => {
    try {
      const response = await axios.post('/api/submit-sitemap', {
        sitemapPath: sitemapPath.value,
      });
      alert(response.data.success);
    } catch (error) {
      alert(error.response.data.error);
    }
  };
  </script>
  