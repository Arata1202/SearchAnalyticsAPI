<template>
  <MainLayout currentId="Sitemap">
    <div class="px-4 sm:px-6 lg:px-8">
      <div>
      <div class="sm:hidden">
        <label for="tabs" class="sr-only">Select a tab</label>
        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
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
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:max-w-none">
            <dl class="mt-16 grid grid-cols-1 gap-0.5 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-4">
              <div class="flex flex-col bg-gray-400/5 p-8">
                <dt class="text-sm font-semibold leading-6 text-gray-600">Sitemap</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900">{{ sitemapCount }}</dd>
              </div>
          </dl>
        </div>
      </div>
      <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300">
              <thead>
                <tr>
                  <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0" style="width: 52%;">URL</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Status</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Type</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Index</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Submitted at</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="(sitemap, index) in sitemaps" :key="index">
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ sitemap.path }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Success</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ sitemap.contents[0].type }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ sitemap.contents[0].submitted }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ formatDate(sitemap.lastSubmitted) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
</MainLayout>
</template>

<script setup>
import MainLayout from '../Layouts/MainLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const tabs = [
  { name: 'Sitemap', href: 'sitemap', current: true, id: "Sitemap" },
  { name: 'Regist Sitemap', href: 'newsitemap', current: false, id: "NewSitemap" },
]

const sitemaps = ref([]);
const sitemapCount = ref(0);

const fetchData = async () => {
  try {
    const response = await axios.get('/api/sitemaps');
    sitemaps.value = response.data.sitemaps;
    sitemapCount.value = sitemaps.value.length;
  } catch (error) {
    console.error('Error fetching sitemap data:', error);
  }
};

onMounted(() => {
  fetchData();
});

const formatDate = (dateString) => {
  const date = new Date(dateString);
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  const seconds = String(date.getSeconds()).padStart(2, '0');
  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};
</script>
