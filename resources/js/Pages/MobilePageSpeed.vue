<template>
    <MainLayout currentId="Speed">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="hidden sm:block">
          <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
              <a v-for="tab in tabs" :key="tab.name" :href="tab.href" :class="[tab.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']" :aria-current="tab.current ? 'page' : undefined">{{ tab.name }}</a>
            </nav>
          </div>
        </div>
        <div class="sm:flex-auto" style="margin-top: 30px;">
          <h1 class="text-base font-semibold leading-6 text-gray-900" style="font-size: 30px;">Page Speed｜Mobile</h1>
        </div>
        <div v-if="isLoading" class="text-center py-8" style="margin-top:100px;">
        <span style="font-size:30px;">Loading...</span>
        <br><br>
        <span style="font-size:20px;">データの取得には数分かかる場合もあります</span>
      </div>
        <div v-if="data && data.mobile && data.desktop">
          <div class="bg-white py-24 sm:py-32" style="padding-top: 0; margin: auto;">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:max-w-none">
            <dl class="mt-16 grid grid-cols-1 gap-0.5 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-4">
              <div class="flex flex-col bg-gray-400/5 p-8">
                <dt class="text-sm font-semibold leading-6 text-gray-600">Mobile</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900">{{ data.mobile.performanceScore*100 }}%</dd>
              </div>
            <div class="flex flex-col bg-gray-400/5 p-8">
              <dt class="text-sm font-semibold leading-6 text-gray-600">PC</dt>
              <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900">{{ data.desktop.performanceScore*100 }}%</dd>
            </div>
            <div class="flex flex-col bg-gray-400/5 p-8">
              <dt class="text-sm font-semibold leading-6 text-gray-600">Average</dt>
              <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900">{{ ((data.mobile.performanceScore+data.desktop.performanceScore)/2*100).toFixed(1) }}%</dd>
            </div>
            <div class="flex flex-col bg-gray-400/5 p-8">
              <dt class="text-sm font-semibold leading-6 text-gray-600">Potential</dt>
              <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900">{{ Math.abs((data.desktop.performanceScore - data.mobile.performanceScore)*100).toFixed(1) }}%</dd>
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
                      <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0" style="width: 40%;">Detail｜Mobile</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 30%;">Value</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 50%;">Optimize</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr v-for="(value, index, key) in data.desktop.diagnostics" :key="index">
                      <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ translations[index] || index }}</td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ (Math.round(value).toFixed(0)) }} {{ units[index] || '' }}</td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ optimize[index] || '' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
    </div>
        </div>
      </div>
    </MainLayout>
  </template>
  
  <script setup>
  import MainLayout from '../Layouts/MainLayout.vue';
  import { ref, onMounted, watch } from 'vue';
  import axios from 'axios';

  const isLoading = ref(false);
  
  const tabs = [
    { name: 'Mobile', href: 'mobilepagespeed', current: true, id: "PageSpeed" },
    { name: 'PC', href: 'pagespeed', current: false, id: "PageSpeed" },
  ]
  
const optimize = {
  mainDocumentTransferSize: 'メインドキュメント のサイズを最小化しましょう',
  numStylesheets: '使用していない CSS は削除しましょう',
  numScripts: '使用していない JavaScript は削除しましょう',
  totalByteWeight: '総バイトサイズ は最適化してください',
  maxRtt: '高い RTT はユーザー体験を低下させます',
  rtt: '高い RTT はユーザー体験を低下させます',
  numRequests: 'リクエスト は最小限にすることが推奨されます',
  numTasksOver100ms: 'タスク が長時間実行されないようにしてください',
  totalTaskTime: 'タスク が長時間実行されないようにしてください',
  numTasksOver500ms: 'タスク が長時間実行されないようにしてください',
  numTasks: 'タスク は最小限にすることが推奨されます',
  numFonts: 'フォント は最小限にすることが推奨されます',
  maxServerLatency: '高レイテンシ はユーザー体験を低下させます',
  throughput: '高スループット は効率的なデータ処理を示します',
  numTasksOver10ms: 'タスク が長時間実行されないようにしてください',
  numTasksOver50ms: 'タスク が長時間実行されないようにしてください',
  numTasksOver25ms: 'タスク が長時間実行されないようにしてください',
};

const translations = {
  mainDocumentTransferSize: 'メインドキュメントの転送サイズ',
  numStylesheets: 'CSS',
  numScripts: 'JavaScript',
  totalByteWeight: '総バイトサイズ',
  maxRtt: '最大往復時間｜RTT',
  rtt: '往復時間｜RTT',
  numRequests: 'リクエスト',
  numTasksOver100ms: '100msを超えるタスク',
  totalTaskTime: 'タスクの総実行時間',
  numTasksOver500ms: '500msを超えるタスク',
  numTasks: 'タスク',
  numFonts: 'フォント',
  maxServerLatency: '最大サーバーレイテンシ',
  throughput: 'スループット',
  numTasksOver10ms: '10msを超えるタスク',
  numTasksOver50ms: '50msを超えるタスク',
  numTasksOver25ms: '25msを超えるタスク',
};

const units = {
  mainDocumentTransferSize: 'Bytes',
  numStylesheets: '件',
  numScripts: '件',
  totalByteWeight: 'Bytes',
  maxRtt: 'ms',
  rtt: 'ms',
  numRequests: '件',
  numTasksOver100ms: '件',
  totalTaskTime: 'ms',
  numTasksOver500ms: '件',
  numTasks: '件',
  numFonts: '件',
  maxServerLatency: 'ms',
  throughput: 'B/s',
  numTasksOver10ms: '件',
  numTasksOver50ms: '件',
  numTasksOver25ms: '件',
};

  const url = ref('https://mochaccinoblog.com');
  const data = ref(null);
  
  async function fetchPageSpeedData() {
    if (!url.value) {
      alert('Please enter a URL');
      return;
    }
    isLoading.value = true;
  try {
    const response = await axios.get(`/api/pagespeed?url=${encodeURIComponent(url.value)}`);
    data.value = response.data;
  } catch (error) {
    console.error('Error fetching PageSpeed data:', error);
  } finally {
    isLoading.value = false;
  }
    try {
      const response = await axios.get(`/api/pagespeed?url=${encodeURIComponent(url.value)}`);
      data.value = response.data;
    } catch (error) {
      console.error('Error fetching PageSpeed data:', error);
    }
  }
  
  onMounted(fetchPageSpeedData);
  </script>
  