export function strLimit(text: string, limit: number = 100, end: string = '...'): string {
  if (!text) return ''
  return text.length <= limit ? text : text.substring(0, limit) + end
}