<template>
  <MainLayout currentId="Query">
    <div class="px-4 sm:px-6 lg:px-8">
      <!-- タブ -->
      <!-- <div>
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
  </div> -->
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-base font-semibold leading-6 text-gray-900" style="font-size: 30px;">Search Performance</h1>
        </div>
        <div style="margin-right: 10px;">
            <select id="tabs" v-model="selectedTabId" @change="onTabChange" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-indigo-600 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
              <option v-for="tab in tabs" :key="tab.id" :value="tab.id">{{ tab.name }}</option>
            </select>
          </div>
        <div>
          <select v-model="selectedPeriod" id="location" name="location" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-indigo-600 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
            <option value="0">Latest Day</option>
            <option value="7">Last 7 Days</option>
            <option value="28">Last 28 Days</option>
            <option value="90">Last 3 Months</option>
            <option value="180">Last 6 Months</option>
            <option value="365">Last 12 Months</option>
            <option value="488">Last 16 Months</option>
          </select>
        </div>
      </div>
      <div class="bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
          <div class="mx-auto max-w-2xl lg:max-w-none">
            <dl class="mt-16 grid grid-cols-1 gap-0.5 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-4">
              <div v-for="stat in stats" :key="stat.id" class="flex flex-col bg-green-500/5 p-8">
                <dt class="text-sm font-semibold leading-6 text-gray-600">{{ stat.name }}</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-green-500">{{ stat.value }}</dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
      <div class="mt-8 flow-root" style="margin-top: 70px;">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300">
              <thead>
                <tr>
                  <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0" style="width: 52%;">Query</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Clicks</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Impression</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">CTR (%)</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Position</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
              <tr v-for="item in paginatedData" :key="item.keys[0]">
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ item.keys[0] }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ item.clicks }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ item.impressions }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ ((item.clicks / item.impressions) * 100).toFixed(2) }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ (Math.round(item.position * 100) / 100).toFixed(2) }}</td>
                </tr>
              </tbody>
            </table>
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
import { ref, watch,computed } from 'vue';
import axios from 'axios';

const isLoading = ref(false);

const tabs = [
  { name: 'Search', href: 'searchquery', current: true, id: "SearchQuery" },
  { name: 'Page', href: 'pagequery', current: false, id: "PageQuery" },
  { name: 'Country', href: 'countryquery', current: false, id: "CountryQuery" },
  { name: 'Device', href: 'devicequery', current: false, id: "DeviceQuery" },
  { name: 'Date', href: 'datequery', current: false, id: "DateQuery" },
]

const getPath = () => window.location.pathname.split('/').pop();
const selectedTabId = ref(tabs.find(tab => tab.href.includes(getPath()))?.id || tabs[0].id);

const onTabChange = () => {
  const selectedTab = tabs.find(tab => tab.id === selectedTabId.value);
  window.location.href = selectedTab.href;
};

const searchAnalyticsData = ref([]);
const selectedPeriod = ref('28');
const error = ref(null);

watch(selectedPeriod, (newValue) => {
  fetchData(newValue);
});

const fetchData = async (days) => {
  error.value = null;
  let endDate = new Date();
  let startDate = new Date();
  
  switch (days) {
    case '0':
      startDate.setDate(endDate.getDate() - 3);
      break;
    case '7':
      startDate.setDate(endDate.getDate() - 7);
      break;
    case '28':
      startDate.setDate(endDate.getDate() - 28);
      break;
    case '90':
    startDate.setMonth(endDate.getMonth() - 3);
    startDate.setDate(endDate.getDate());
    if (startDate.getMonth() !== (endDate.getMonth() - 16 + 12) % 12) {
      startDate.setDate(0);
    }
    break;
    case '180':
    startDate.setMonth(endDate.getMonth() - 6);
    startDate.setDate(endDate.getDate());
    if (startDate.getMonth() !== (endDate.getMonth() - 16 + 12) % 12) {
      startDate.setDate(0);
    }
    break;
    case '365':
    startDate.setMonth(endDate.getMonth() - 12);
    startDate.setDate(endDate.getDate());
    if (startDate.getMonth() !== (endDate.getMonth() - 16 + 12) % 12) {
      startDate.setDate(0);
    }
    break;
    case '488':
    startDate.setMonth(endDate.getMonth() - 16);
    startDate.setDate(endDate.getDate());
    if (startDate.getMonth() !== (endDate.getMonth() - 16 + 12) % 12) {
      startDate.setDate(0);
    }
    break;
  }

  try {
    let response = await axios.post('/api/search-query', {
      startDate: startDate.toISOString().split('T')[0],
      endDate: endDate.toISOString().split('T')[0],
    });

    if (response.data) {
      searchAnalyticsData.value = response.data.map(item => ({
        ...item,
        ctr: item.ctr * 100,
        position: item.position,
      }));
    }
  } catch (err) {
    error.value = {
      message: err.message,
      status: err.response ? err.response.status : 'N/A',
    };
  }
}
  
fetchData(selectedPeriod.value);

const calculateStats = () => {
  const totalClicks = searchAnalyticsData.value.reduce((acc, curr) => acc + curr.clicks, 0);
  const totalImpressions = searchAnalyticsData.value.reduce((acc, curr) => acc + curr.impressions, 0);
  const totalCTR = searchAnalyticsData.value.reduce((acc, curr) => acc + curr.ctr, 0) / searchAnalyticsData.value.length;
  const totalPosition = searchAnalyticsData.value.reduce((acc, curr) => acc + curr.position, 0) / searchAnalyticsData.value.length;

  return [
      { id: 1, name: 'Total Clicks', value: totalClicks },
      { id: 2, name: 'Total Impressions', value: totalImpressions },
      { id: 3, name: 'Average CTR', value: totalCTR.toFixed(2) + '%' },
      { id: 4, name: 'Average Position', value: totalPosition.toFixed(2) }
    ];
};

const currentPage = ref(1);
const itemsPerPage = ref(10);

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return searchAnalyticsData.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(searchAnalyticsData.value.length / itemsPerPage.value);
});

function changePage(page) {
  currentPage.value = page;
}
const visiblePages = computed(() => {
  const pages = [];
  const total = totalPages.value;
  const current = currentPage.value;
  const range = 2;

  for (let i = 1; i <= total; i++) {
    if (i === 1 || i === total || (i >= current - range && i <= current + range)) {
      pages.push(i);
    }
  }

  return pages;
});

const stats = ref([]);
watch(searchAnalyticsData, () => {
  stats.value = calculateStats();
});

</script>
