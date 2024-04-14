<template>
    <MainLayout currentId="Index">
    <div class="px-4 sm:px-6 lg:px-8">
    <div>
      <!-- <div class="hidden sm:block">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
          <a v-for="tab in tabs" :key="tab.name" :href="tab.href" :class="[tab.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']" :aria-current="tab.current ? 'page' : undefined">{{ tab.name }}</a>
        </nav>
      </div>
    </div> -->
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold leading-6 text-gray-900" style="font-size: 30px;">Indexed</h1>
      </div>
    </div>
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:max-w-none">
            <dl class="mt-16 grid grid-cols-1 gap-0.5 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-4">
              <div class="flex flex-col bg-green-500/5 p-8">
                <dt class="text-sm font-semibold leading-6 text-gray-600">index</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-green-500">{{ totalResults }}</dd>
              </div>
          </dl>
        </div>
      </div>
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div v-if="searchResults" class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300">
              <thead>
                <tr>
                  <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Title｜URL</th>
                  <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Snippet｜Meta tags</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="item in searchResults.items" :key="item.cacheId">
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ item.title }}<br>{{ item.link }}</td>
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ item.snippet }}{{ item.metatags }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
        <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0" style="margin-top: 50px;">
          <div class="-mt-px flex w-0 flex-1">
            <button
              @click="changePage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-indigo-600 hover:border-gray-300 hover:text-gray-700"
            >
              <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-indigo-600" aria-hidden="true" />
              Previous
            </button>
          </div>
          <div class="hidden md:-mt-px md:flex">
            <button
              v-for="page in visiblePages"
              :key="page"
              @click="changePage(page)"
              class="inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium hover:border-gray-300 hover:text-gray-700"
              :class="{ 'border-indigo-500 text-indigo-600': currentPage === page }"
            >
              {{ page }}
            </button>
            <span v-if="needsEllipsis" class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500">...</span>
          </div>
          <div class="-mt-px flex w-0 flex-1 justify-end">
            <button
              @click="changePage(currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-indigo-600 hover:border-gray-300 hover:text-gray-700"
            >
              Next
              <ArrowLongRightIcon class="ml-3 h-5 w-5 text-indigo-600" aria-hidden="true" />
            </button>
          </div>
        </nav>
    </div>
    </div>
    </MainLayout>
  </template>
  
  <script setup>
  import { ArrowLongLeftIcon, ArrowLongRightIcon } from '@heroicons/vue/20/solid'
  import MainLayout from '../Layouts/MainLayout.vue';
  import { ref,onMounted,computed } from 'vue';
  import axios from 'axios';

  const tabs = [
  { name: 'Index', href: 'submitindex', current: true, id: "SubmitIndex" },
  // { name: 'Regist Index', href: 'registindex', current: true, id: "RegistIndex" },
]
  
  const searchQuery = ref('');
  const searchResults = ref(null);

  const totalResults = ref(0);
  const currentPage = ref(1);
  const totalPages = ref(0);
  const visiblePages = computed(() => {
  const pages = [];
  const range = 2;
  for (let i = Math.max(1, currentPage.value - range); i <= Math.min(totalPages.value, currentPage.value + range); i++) {
    pages.push(i);
  }
  return pages;
});
  
const fetchSearchResults = async () => {
  const start = (currentPage.value - 1) * 10 + 1;
  try {
    const response = await axios.get(`/api/search?q=${encodeURIComponent(searchQuery.value)}&start=${start}`);
    searchResults.value = response.data;
    totalResults.value = parseInt(response.data.totalResults);
    totalPages.value = Math.ceil(response.data.totalResults / 10);
  } catch (error) {
    console.error('Error fetching search results:', error);
  }
};

function changePage(page) {
  currentPage.value = page;
  fetchSearchResults();
}



  onMounted(fetchSearchResults);
  </script>
  