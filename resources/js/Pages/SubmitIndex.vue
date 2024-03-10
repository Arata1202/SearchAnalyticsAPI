<template>
    <MainLayout currentId="Index">
    <div class="px-4 sm:px-6 lg:px-8">
    <div>
      <div class="hidden sm:block">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
          <a v-for="tab in tabs" :key="tab.name" :href="tab.href" :class="[tab.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']" :aria-current="tab.current ? 'page' : undefined">{{ tab.name }}</a>
        </nav>
      </div>
    </div>
    <div class="sm:flex-auto" style="margin-top: 30px;">
        <h1 class="text-base font-semibold leading-6 text-gray-900" style="font-size: 30px;">Index</h1>
    </div>
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div v-if="searchResults" class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300">
              <thead>
                <tr>
                  <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0" style="width: 52%;">URL</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="item in searchResults.items" :key="item.cacheId">
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ item.title }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    </div>
    </MainLayout>
  </template>
  
  <script setup>
  import MainLayout from '../Layouts/MainLayout.vue';
  import { ref,onMounted } from 'vue';
  import axios from 'axios';

  const tabs = [
  { name: 'Index', href: 'submitindex', current: true, id: "SubmitIndex" },
  // { name: 'Regist Index', href: 'registindex', current: true, id: "RegistIndex" },
]
  
  const searchQuery = ref('');
  const searchResults = ref(null);
  
  const fetchSearchResults = async () => {
    try {
      const response = await axios.get(`/api/search?q=${encodeURIComponent(searchQuery.value)}`);
      searchResults.value = response.data;
    } catch (error) {
      console.error('Error fetching search results:', error);
    }
  };
  onMounted(fetchSearchResults);
  </script>
  