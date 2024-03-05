<template>
    <MainLayout currentId="PageQuery">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">ページ</h1>
            <select v-model="selectedPeriod">
              <option value="0">最新日</option>
              <option value="7">過去 7 日間</option>
              <option value="28">過去 28 日間</option>
              <option value="90">過去 3 か月間</option>
              <option value="180">過去 6 か月間</option>
              <option value="365">過去 12 か月間</option>
              <option value="488">過去 16 か月間</option>
            </select>
          </div>
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
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0" style="width: 52%;">ページURL</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">クリック</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">表示回数</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">CTR (%)</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">平均表示順位</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="item in pageAnalyticsData" :key="item.page">
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
  
  const pageAnalyticsData = ref([]);
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
      let response = await axios.post('/api/page-query', {
        startDate: startDate.toISOString().split('T')[0],
        endDate: endDate.toISOString().split('T')[0],
      });
  
      if (response.data) {
        pageAnalyticsData.value = response.data.map(item => ({
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
    const totalClicks = pageAnalyticsData.value.reduce((acc, curr) => acc + curr.clicks, 0);
    const totalImpressions = pageAnalyticsData.value.reduce((acc, curr) => acc + curr.impressions, 0);
    const totalCTR = pageAnalyticsData.value.reduce((acc, curr) => acc + curr.ctr, 0) / pageAnalyticsData.value.length;
    const totalPosition = pageAnalyticsData.value.reduce((acc, curr) => acc + curr.position, 0) / pageAnalyticsData.value.length;
  
    return [
      { id: 1, name: '合計クリック数', value: totalClicks },
      { id: 2, name: '合計表示回数', value: totalImpressions },
      { id: 3, name: '平均CTR', value: totalCTR.toFixed(2) + '%' },
      { id: 4, name: '平均掲載順位', value: totalPosition.toFixed(2) }
    ];
  };
  
  const stats = ref([]);
  watch(pageAnalyticsData, () => {
    stats.value = calculateStats();
  });

  const decodeUrl = (url) => {
  if (!url) return '';
  const cleanedUrl = url.replace(/^\["|"\]$/g, '');
  return decodeURIComponent(cleanedUrl);
};
  </script>
  