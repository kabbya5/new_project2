export interface PostCommentForm{
    [key: number]: {
        comment: string|null,
        image:File|null,
        previewUrl:string|null
    }
}