<template>
  <!-- 画像を表示するコンポーネント -->
  <div class="flex flex-wrap gap-1">
    <!-- 画像の並び替えを可能にするSortableコンポーネント -->
    <Sortable
      :list="imageUrls"
      item-key="id"
      class="flex gap-1 flex-wrap"
      @end="onImageDragEnd"
    >
      <!-- 画像アイテムのテンプレート -->
      <template #item="{element: image, index}">
        <div
          class="relative w-[120px] h-[120px] rounded border border-dashed flex items-center justify-center hover:border-purple-500 overflow-hidden">
          <!-- 画像の表示 -->
          <img :src="image.url" class="max-w-full max-h-full" :class="image.deleted ? 'opacity-50' : ''">
          <!-- 削除された画像のラベル -->
          <small v-if="image.deleted"
                 class="absolute left-0 bottom-0 right-0 py-1 px-2 bg-black w-100 text-white justify-between items-center flex">
            To be deleted
            <!-- 画像の元に戻すアイコン -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="w-4 h-4 cursor-pointer" @click="revertImage(image)">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
            </svg>
          </small>
          <!-- 画像を削除するアイコン -->
          <span class="absolute top-1 right-1 cursor-pointer" @click="removeImage(image)">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
              <path
                d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
            </svg>
          </span>
        </div>
      </template>
    </Sortable>
    <!-- 画像をアップロードするためのコンポーネント -->
    <div
      class="relative w-[120px] h-[120px] rounded border border-dashed flex items-center justify-center hover:border-purple-500 overflow-hidden">
      <span>
        Upload
      </span>
      <!-- ファイルを選択するinput -->
      <input type="file" class="absolute left-0 top-0 bottom-0 right-0 w-full h-full opacity-0"
             @change="onFileChange" multiple>
    </div>
  </div>
</template>


<script setup>
// Imports
import {Sortable} from "sortablejs-vue3"; // Sortable.js-Vue3 をインポート
import {v4 as uuidv4} from 'uuid'; // UUIDv4 をインポート
import {onMounted, ref, watch} from "vue"; // Vue の onMounted, ref, watch をインポート

// Uses

// Refs

const files = ref([]) // ファイルの参照
const imageUrls = ref([]) // 画像の URL の参照
const deletedImages = ref([]) // 削除された画像の参照
const imagePositions = ref([]) // 画像の位置の参照

// Props & Emit
const props = defineProps(['modelValue', 'deletedImages', 'images']) // プロパティの定義
const emit = defineEmits(['update:modelValue', 'update:deletedImages', 'update:imagePositions']) // イベントの定義

// Computed

// Methods

// ファイルが選択されたときの処理
function onFileChange($event) {
  const chosenFiles = [...$event.target.files];
  files.value = [...files.value, ...chosenFiles];
  $event.target.value = ''
  const allPromises = [];
  for (let file of chosenFiles) {
    file.id = uuidv4()
    const promise = readFile(file)
    allPromises.push(promise)
    promise
      .then(url => {
        imageUrls.value.push({
          url,
          id: file.id
        })
      })
  }
  Promise.all(allPromises)
    .then(() => {
      updateImagePositions()
    })
  emit('update:modelValue', files.value)
}

// ファイルを読み込む関数
function readFile(file) {
  return new Promise((resolve, reject) => {
    const fileReader = new FileReader()
    fileReader.readAsDataURL(file)
    fileReader.onload = () => {
      resolve(fileReader.result)
    }
    fileReader.onerror = reject
  })
}

// 画像を削除する関数
function removeImage(image) {
  if (image.isProp) {
    deletedImages.value.push(image.id)
    image.deleted = true;

    emit('update:deletedImages', deletedImages.value)
  } else {
    files.value = files.value.filter(f => f.id !== image.id)
    imageUrls.value = imageUrls.value.filter(f => f.id !== image.id)

    emit('update:modelValue', files.value)
  }

  updateImagePositions();
}

// 画像を元に戻す関数
function revertImage(image) {
  if (image.isProp) {
    deletedImages.value = deletedImages.value.filter(id => id !== image.id)
    image.deleted = false;

    emit('update:deletedImages', deletedImages.value)
  }
}

// 画像がドラッグされたときの処理
function onImageDragEnd(ev) {
  console.log(ev)

  const {newIndex, oldIndex} = ev;

  const [tmp] = imageUrls.value.splice(oldIndex, 1)
  imageUrls.value.splice(newIndex, 0, tmp)

  updateImagePositions()
}

// 画像の位置を更新する関数
function updateImagePositions() {
  /**
   * [
   *   [1, 1],
   *   [4, 2],
   *   [5, 3],
   * ]
   */
  imagePositions.value = Object.fromEntries(
    imageUrls.value.filter(im => !im.deleted)
      .map((im, ind) => [im.id, ind + 1])
  )

  emit('update:imagePositions', imagePositions.value)
}

// Hooks

// プロパティ 'images' の変更を監視する
watch('props.images', () => {
  console.log(props.images)
  imageUrls.value = [
    ...imageUrls.value,
    ...props.images.map(im => ({
      ...im,
      isProp: true
    }))
  ]

  updateImagePositions()
}, {immediate: true, deep: true})

// マウント後の処理
onMounted(() => {
  emit('update:modelValue', [])
  emit('update:deletedImages', deletedImages.value)
})

</script>

<style scoped>

</style>
