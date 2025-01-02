<script setup lang="ts">
import {onBeforeUnmount, onMounted, PropType, ref} from "vue";
import {Head} from "@inertiajs/vue3";
import FileData = App.Data.FileData;
import File from "@/Components/FileComponent.vue";
import FileComponent from "@/Components/FileComponent.vue";
import HeaderLayout from "@/Layouts/HeaderLayout.vue";

const props = defineProps({
  path: String,
  files: {type: Array as PropType<FileData[]>}
})
let isCtrlPressed = ref(false);

const handleKeyDown = (event: KeyboardEvent) => {
    if ((event.key === 'Control' || event.ctrlKey) && !isCtrlPressed.value) {
        isCtrlPressed.value = true;
    }
};

const handleKeyUp = (event: KeyboardEvent) => {
    if (event.key === 'Control' || event.ctrlKey) {
        isCtrlPressed.value = false;
    }
};

onMounted(() => {
    document.addEventListener('keydown', handleKeyDown);
    document.addEventListener('keyup', handleKeyUp);
});

onBeforeUnmount(() => {
    document.removeEventListener('keydown', handleKeyDown);
    document.removeEventListener('keyup', handleKeyUp);
});
let contextMenu = ref()
let mouseEnterMenu = false;

let isFile = ref(false)
let parentElement = ref()
function contextMenuOpen(event: MouseEvent) {
    event.preventDefault()
    console.log(event.target)
    let menu : HTMLElement = contextMenu.value as HTMLElement
    menu.classList.remove("hidden")
    menu.style.top = event.clientY+"px"
    menu.style.left = event.clientX+"px"
    let target : HTMLElement = event.target as HTMLElement
    parentElement.value = target.closest("[path]")
    if (parentElement.value) {
        isFile.value = true
    }else {
        isFile.value = false
    }
}
async function copyToClipboard(text : string) {
    try {
        await navigator.clipboard.writeText(text);
    } catch (err) {
        console.error("Не удалось скопировать текст:", err);
    }
}
function clickMenuButton(action : string) {
    console.log(parentElement.value)
    let path = props.path
    let actionT = action+""
    if (parentElement.value != null) {
        let FileElement : HTMLElement = parentElement.value as HTMLElement
        path = FileElement!.attributes.getNamedItem("path")?.value
        actionT = "t"+action
    }

    switch (actionT) {
        case 'topen': {
            window.location.href = route('files', {'path': path})
            break
        }
        case 'topenNew': {
            window.open(route('files', {'path': path}))
            break
        }
    }
    switch (action) {
        case 'download': {
            window.open(route('download', {'path': path}));
            break
        }
        case 'copyURL': {
            copyToClipboard(route('files', {'path': path}))
            mouseEnterMenu = false
            closeMenu()
        }
        case 'copyURLdownload': {
            copyToClipboard(route('download', {'path': path}))
            mouseEnterMenu = false
            closeMenu()
        }
    }

}
function mouseEnter() {
    mouseEnterMenu = true
}
function mouseLeave() {
    mouseEnterMenu = false
}
function closeMenu() {
    if (!mouseEnterMenu) {
        let menu : HTMLElement = contextMenu.value
        menu.classList.add("hidden")
    }
}

</script>

<template>
    <Head :title="path+'/'"/>
    <main class="h-[100vh]" @click="closeMenu">
        <HeaderLayout :path="path"></HeaderLayout>
        <section class="grid h-full overflow-scroll bg-[#18191d]" @contextmenu="contextMenuOpen">
            <table class="border-collapse h-min">
                <FileComponent v-for="file in files" class="h-12" v-model:ctrl="isCtrlPressed" :path="path" :file="file!"/>
            </table>
        </section>
        <menu type="context" ref="contextMenu" @mouseenter="mouseEnter" @mouseleave="mouseLeave"
              class="absolute flex flex-col w-1/5 top-0 left-0 bg-[#23282c] rounded-md text-white hidden" style='border: 1px solid #4c5155'>
            <button class="buttonMenu" @click="clickMenuButton('open')"  :class="{'disable': !isFile}">
                Открыть файл
            </button>
            <button class="buttonMenu"  @click="clickMenuButton('openNew')" :class="{'disable': !isFile}">
                Открыть файл в новой вкладке
            </button>
            <hr class="border-[#3c464f]">
            <button class="buttonMenu"  @click="clickMenuButton('download')">
                Скачать файл
            </button>
            <hr class="border-[#3c464f]">
            <button class="buttonMenu"  @click="clickMenuButton('copyURL')">
                Скопировать ссылку
            </button>
            <button class="buttonMenu"  @click="clickMenuButton('copyURLdownload')">
                Скопировать ссылку на скачивание
            </button>
        </menu>
    </main>


</template>

<style scoped>
  tr:nth-child(odd) {
    background: rgba(57, 57, 57, 0.4);
  }
  .buttonMenu {
      padding: 5px 10px;
      text-align: left;
      width: 100%;
      transition: background-color .1s;
      box-sizing: border-box  !important;
  }
  .buttonMenu:hover {
      background: rgba(33, 149, 137, 0.53);
      border: 1px solid #219589 ;

  }
  .buttonMenu.disable {
      color: #4c5155;
  }
  .buttonMenu.disable:hover {
      background: rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(0, 0, 0, 0.1);
  }
</style>
