import { format } from 'date-fns'

export function formatDate(dateString) {
  return format(new Date(dateString), 'dd/MM/yyyy')
}

export function convertWordToNumber(word) {
  const mapping = {
    "ONE": "1",
    "TWO": "2",
    "THREE": "3",
    "FOUR": "4",
    "FIVE": "5",
    // Ajoutez plus de mappings si nécessaire
  };

  return mapping[word.toUpperCase()] || null;
}