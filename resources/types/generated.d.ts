declare namespace App.Data {
export type FileData = {
name: string;
size: string;
author: string | null;
group: string | null;
perm: number;
permS: string | null;
mimetype: string;
created: string;
updated: string;
isFolder: boolean;
};
}
