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
            <h1 class="text-base font-semibold leading-6 text-gray-900" style="font-size: 30px;">Country Performance</h1>
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
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0" style="width: 52%;">Country</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Clisks</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Impression</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">CTR (%)</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" style="width: 12%;">Position</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="item in countryAnalyticsData" :key="item.page">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ countryTranslations[item.keys[0]] || item.keys[0] }}</td>
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
  { name: 'Country Performance', href: 'countryquery', current: true, id: "CountryQuery" },
  { name: 'Device Performance', href: 'devicequery', current: false, id: "DeviceQuery" },
  { name: 'Date Performance', href: 'datequery', current: false, id: "DateQuery" },
]
  
  const countryAnalyticsData = ref([]);
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
      let response = await axios.post('/api/country-query', {
        startDate: startDate.toISOString().split('T')[0],
        endDate: endDate.toISOString().split('T')[0],
      });
  
      if (response.data) {
        countryAnalyticsData.value = response.data.map(item => ({
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
    const totalClicks = countryAnalyticsData.value.reduce((acc, curr) => acc + curr.clicks, 0);
    const totalImpressions = countryAnalyticsData.value.reduce((acc, curr) => acc + curr.impressions, 0);
    const totalCTR = countryAnalyticsData.value.reduce((acc, curr) => acc + curr.ctr, 0) / countryAnalyticsData.value.length;
    const totalPosition = countryAnalyticsData.value.reduce((acc, curr) => acc + curr.position, 0) / countryAnalyticsData.value.length;
  
    return [
      { id: 1, name: 'Total Clicks', value: totalClicks },
      { id: 2, name: 'Total Impressions', value: totalImpressions },
      { id: 3, name: 'Average CTR', value: totalCTR.toFixed(2) + '%' },
      { id: 4, name: 'Average Position', value: totalPosition.toFixed(2) }
    ];
  };
  
  const stats = ref([]);
  watch(countryAnalyticsData, () => {
    stats.value = calculateStats();
  });

  const decodeUrl = (url) => {
  if (!url) return '';
  const cleanedUrl = url.replace(/^\["|"\]$/g, '');
  return decodeURIComponent(cleanedUrl);
};

const countryTranslations = {
  jpn: '日本',
  deu: 'ドイツ',
  fra: 'フランス',
  usa: 'アメリカ合衆国',
  ala: 'オーランド諸島',
  are: 'アラブ首長国連邦',
  arg: 'アルゼンチン',
  aus: 'オーストラリア',
  bfa: 'ブルキナファソ',
  bgd: 'バングラデシュ',
  bhs: 'バハマ',
  bih: 'ボスニア・ヘルツェゴビナ',
  blr: 'ベラルーシ',
  bra: 'ブラジル',
  brb: 'バルバドス',
  can: 'カナダ',
  chn: '中国',
  col: 'コロンビア',
  cze: 'チェコ',
  dnk: 'デンマーク',
  dza: 'アルジェリア',
  ecu: 'エクアドル',
  egy: 'エジプト',
  esp: 'スペイン',
  fin: 'フィンランド',
  gbr: 'イギリス',
  glp: 'グアドループ',
  grc: 'ギリシャ',
  grd: 'グレナダ',
  hkg: '香港',
  hun: 'ハンガリー',
  idn: 'インドネシア',
  ind: 'インド',
  irl: 'アイルランド',
  irq: 'イラク',
  ita: 'イタリア',
  kaz: 'カザフスタン',
  kor: '韓国',
  lka: 'スリランカ',
  ltu: 'リトアニア',
  mda: 'モルドバ',
  mex: 'メキシコ',
  moz: 'モザンビーク',
  nld: 'オランダ',
  nzl: 'ニュージーランド',
  omn: 'オマーン',
  pak: 'パキスタン',
  per: 'ペルー',
  phl: 'フィリピン',
  pol: 'ポーランド',
  prt: 'ポルトガル',
  rou: 'ルーマニア',
  rus: 'ロシア',
  rwa: 'ルワンダ',
  sau: 'サウジアラビア',
  sgp: 'シンガポール',
  srb: 'セルビア',
  swe: 'スウェーデン',
  tha: 'タイ',
  tur: 'トルコ',
  twn: '台湾',
  ukr: 'ウクライナ',
  vnm: 'ベトナム',
  zaf: '南アフリカ',
  zzz: 'その他',
};

  </script>
  