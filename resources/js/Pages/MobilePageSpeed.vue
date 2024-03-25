<template>
    <MainLayout currentId="Speed">
      <div class="px-4 sm:px-6 lg:px-8">
        <!-- <div class="hidden sm:block">
          <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
              <a v-for="tab in tabs" :key="tab.name" :href="tab.href" :class="[tab.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']" :aria-current="tab.current ? 'page' : undefined">{{ tab.name }}</a>
            </nav>
          </div>
        </div> -->
        <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-base font-semibold leading-6 text-gray-900" style="font-size: 30px;">Page Speed｜Mobile</h1>
        </div>
        <div style="margin-right: 10px;">
            <select id="tabs" v-model="selectedTabId" @change="onTabChange" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-inindigo-600 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
              <option v-for="tab in tabs" :key="tab.id" :value="tab.id">{{ tab.name }}</option>
            </select>
          </div>
        </div>
        <div v-if="isLoading" class="text-center py-8" style="margin-top:100px;">
        <span style="font-size:30px;">Loading...</span>
        <br><br>
        <span style="font-size:20px;">データの取得には数分かかる場合もあります</span>
      </div>
        <div v-if="data && data.mobile && data.desktop">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:max-w-none">
            <dl class="mt-16 grid grid-cols-1 gap-0.5 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-4">
              <div class="flex flex-col bg-green-500/5 p-8">
                <dt class="text-sm font-semibold leading-6 text-gray-600">Mobile</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-green-500">{{ data.mobile.performanceScore*100 }}%</dd>
              </div>
            <div class="flex flex-col bg-green-500/5 p-8">
              <dt class="text-sm font-semibold leading-6 text-gray-600">PC</dt>
              <dd class="order-first text-3xl font-semibold tracking-tight text-green-500">{{ data.desktop.performanceScore*100 }}%</dd>
            </div>
            <div class="flex flex-col bg-green-500/5 p-8">
              <dt class="text-sm font-semibold leading-6 text-gray-600">Average</dt>
              <dd class="order-first text-3xl font-semibold tracking-tight text-green-500">{{ ((data.mobile.performanceScore+data.desktop.performanceScore)/2*100).toFixed(1) }}%</dd>
            </div>
            <div class="flex flex-col bg-green-500/5 p-8">
              <dt class="text-sm font-semibold leading-6 text-gray-600">Potential</dt>
              <dd class="order-first text-3xl font-semibold tracking-tight text-green-500">{{ Math.abs((data.desktop.performanceScore - data.mobile.performanceScore)*100).toFixed(1) }}%</dd>
            </div>
          </dl>
        </div>
      </div>
      <div class="mt-8 flow-root" style="margin-top: 70px;">
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
                    <tr v-for="item in paginatedData" :key="item[0]">
                      <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ translations[item[0]] || item[0] }}</td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ (Math.round(item[1]).toFixed(0)) }} {{ units[item[0]] || '' }}</td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ optimize[item[0]] || '' }}</td>
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
  import { ref, onMounted, computed } from 'vue';
  import axios from 'axios';

  const isLoading = ref(false);
  
  const tabs = [
    { name: 'Mobile', href: 'mobilepagespeed', current: true, id: "MobilePageSpeed" },
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

const getPath = () => window.location.pathname.split('/').pop();
const selectedTabId = ref(tabs.find(tab => tab.href.includes(getPath()))?.id || tabs[0].id);

const onTabChange = () => {
  const selectedTab = tabs.find(tab => tab.id === selectedTabId.value);
  console.log('Selected tab href:', selectedTab.href);
  window.location.href = selectedTab.href;
};

  const url = ref(import.meta.env.VITE_YOUR_URL);
  const data = ref(null);
  
const currentPage = ref(1);
const itemsPerPage = ref(10);

async function fetchPageSpeedData() {
  isLoading.value = true;
  try {
    const response = await axios.get(`/api/pagespeed?url=${encodeURIComponent(url.value)}`);
    data.value = response.data;
  } catch (error) {
    console.error('Error fetching PageSpeed data:', error);
  } finally {
    isLoading.value = false;
  }
}
  
  onMounted(fetchPageSpeedData);

const paginatedData = computed(() => {
  if (!data.value || !data.value.mobile || !data.value.mobile.diagnostics) {
    return [];
  }
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return Object.entries(data.value.mobile.diagnostics).slice(start, end);
});

const totalPages = computed(() => {
  if (!data.value || !data.value.mobile || !data.value.mobile.diagnostics) {
    return 0;
  }
  return Math.ceil(Object.keys(data.value.mobile.diagnostics).length / itemsPerPage.value);
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
  </script>
  