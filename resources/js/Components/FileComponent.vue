<script setup lang="ts">
import {PropType, ref, watch} from "vue";
import FileData = App.Data.FileData;
import {router} from "@inertiajs/vue3";
import number = CSS.number;

const props = defineProps({
    file: {type: Object as PropType<FileData>, required: true},
    path: {type: String, default: ""},
    icon: {type: Number, default: 64},
    isFull: {type: Boolean, default: false},
    width: {type: Number, default: 64},
    ctrl: {type: Boolean, default: false}
})
let currentIconIndex = 0;
let IconPath = props.file.mimetype.replace("/","-")
if (props.file.isFolder) {
    IconPath = "folder-blue"
}
let IconsPath = [
    IconPath,
    IconPath.replace("text","application"),
    IconPath.replace("script.",""),
    "unknown"
]
let formatedPath = props.path
const AssestPath = '/storage/'+props.icon+'/'
let IconUrl = ref(AssestPath+IconsPath[currentIconIndex]+".svg")
let sizeIndex = 0
const sizeH = [
    "b",
    "Kb",
    "Mb",
    "Gb",
    "Tb",
    "Pb"
]

function convertB2H(size: number) {
    if (size < 1024) {
        let sizeS = sizeH[sizeIndex]
        sizeIndex = 0
        return parseFloat(size.toString()).toFixed(1)+" "+sizeS
    }
    sizeIndex++
    return convertB2H(size/1024)
}
function ErrorImage() {
    currentIconIndex++
    IconUrl.value = AssestPath+IconsPath[currentIconIndex]+".svg"
}

let URLpath = formatedPath+"/"+props.file.name
let url = route('files',{"path": URLpath})
async function open(e: Event) {
    clearSelection()
    if (props.file.isFolder) {
        await router.visit(url)
    }else {
        window.location.href = url

    }
}

function handleMouseDown(event: MouseEvent) {
    if(event.button == 1 || (props.ctrl && event.button == 0)) {
        window.open(url)
    }
}
function clearSelection() {
    const selection = window.getSelection();

    // Check if window.getSelection() is not null and has removeAllRanges method
    if (selection) {
        selection.removeAllRanges();
    } else {
        const docSelection = document.getSelection();

        // Check if document.getSelection() is not null
        if (docSelection) {
            docSelection.removeAllRanges();
        }
    }
}


</script>

<template>
    <tr class="text-white"  @mousedown="handleMouseDown" :path="URLpath" @dblclick="open">
        <td :style="'min-width: '+props.width   +'px'">
            <img :src="IconUrl" class="w-full" :alt="IconPath" @error="ErrorImage">
        </td>
        <td class="w-full text-left cursor-default">
            {{file.name}}
        </td>
        <td v-if="!file.isFolder" class="text-nowrap text-right">
            {{convertB2H(props.file.size)}}
        </td>
        <td v-else>

        </td>
        <td class="text-nowrap text-right">
            {{new Date(Number(file.updated ?? 0)*1000).toLocaleString()}}
        </td>
        <td class="text-nowrap text-right">
            {{new Date(Number(file.updated ?? 0)*1000).toLocaleString()}}
        </td>
    </tr>

</template>
<style scoped>
td {
    padding:0 15px 0;
}
</style>

