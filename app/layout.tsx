import type { Metadata } from 'next'
import './globals.css'

export const metadata: Metadata = {
  title: 'SklepInternetowyWSB',
  description: 'Strona internetowa - sklep internetowy, strona została zrealizowana na potrzeby projektu zaliczeniowego na przedmiot Programowanie w Zastosowaniach',
}

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode
}>) {
  return (
    <html lang="pl" className="dark">
      <body className="min-h-screen bg-background">{children}</body>
    </html>
  )
}
