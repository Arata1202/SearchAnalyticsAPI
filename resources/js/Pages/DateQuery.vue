<template>
    <MainLayout currentId="Query">
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
            <h1 class="text-base font-semibold leading-6 text-gray-900" style="font-size: 30px;">Date Performance</h1>
        </div>
        <select v-model="selectedPeriod">
          <option value="0">Latest Day</option>
            <option value="7">Last 7 Days</option>
            <option value="28">Last 28 Days</option>
            <option value="90">Last 3 Months</option>
            <option value="180">Last 6 Months</option>
            <option value="365">Last 12 Months</option>
            <option value="488">Last 16 Months</option>
        </select>
        </div>
        <div class="bg-white">
          <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:max-w-none">
              <dl class="mt-16 grid grid-cols-1 gap-0.5 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="stat in stats" :key="stat.id" class="flex flex-col bg-gray-400/5 p-8">
                  <dt class="text-sm font-semibold leading-6 text-gray-600">{{ stat.name }}</dt>
                  <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900">{{ stat.value }}</dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
        <div class="mt-8 flow-root">
          <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
              <table class="min-w-full divide-y divide-gray-300">
                <thead>
                  <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0" style="width: 52%;">Date</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Clicks</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Impression</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">CTR (%)</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Position</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="item in dateAnalyticsData" :key="item.page">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ decodeUrl(item.keys[0]) }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ item.clicks }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ item.impressions }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ ((item.clicks / item.impressions) * 100).toFixed(2) }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ (Math.round(item.position * 100) / 100).toFixed(2) }}</td>
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
  import { ref, watch } from 'vue';
  import axios from 'axios';

  const tabs = [
  { name: 'Search Performance', href: 'searchquery', current: false, id: "SearchQuery" },
  { name: 'Page Performance', href: 'pagequery', current: false, id: "PageQuery" },
  { name: 'Country Performance', href: 'countryquery', current: false, id: "CountryQuery" },
  { name: 'Device Performance', href: 'devicequery', current: false, id: "DeviceQuery" },
  { name: 'Date Performance', href: 'datequery', current: true, id: "DateQuery" },
]
  
  const dateAnalyticsData = ref([]);
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
    let response = await axios.post('/api/date-query', {
      startDate: startDate.toISOString().split('T')[0],
      endDate: endDate.toISOString().split('T')[0],
    });

    if (response.data) {
      const sortedData = response.data
        .map(item => ({
          ...item,
          ctr: item.ctr * 100,
          position: item.position,
        }))
        .sort((a, b) => new Date(b.keys[0]) - new Date(a.keys[0])); 

      dateAnalyticsData.value = sortedData;
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
    const totalClicks = dateAnalyticsData.value.reduce((acc, curr) => acc + curr.clicks, 0);
    const totalImpressions = dateAnalyticsData.value.reduce((acc, curr) => acc + curr.impressions, 0);
    const totalCTR = dateAnalyticsData.value.reduce((acc, curr) => acc + curr.ctr, 0) / dateAnalyticsData.value.length;
    const totalPosition = dateAnalyticsData.value.reduce((acc, curr) => acc + curr.position, 0) / dateAnalyticsData.value.length;
  
    return [
      { id: 1, name: 'Total Clicks', value: totalClicks },
      { id: 2, name: 'Total Impressions', value: totalImpressions },
      { id: 3, name: 'Average CTR', value: totalCTR.toFixed(2) + '%' },
      { id: 4, name: 'Average Position', value: totalPosition.toFixed(2) }
    ];
  };
  
  const stats = ref([]);
  watch(dateAnalyticsData, () => {
    stats.value = calculateStats();
  });

  const decodeUrl = (url) => {
  if (!url) return '';
  const cleanedUrl = url.replace(/^\["|"\]$/g, '');
  return decodeURIComponent(cleanedUrl);
};
  </script>
  