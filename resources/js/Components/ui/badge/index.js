import { cva } from "class-variance-authority";

export { default as Badge } from "./Badge.vue";

export const badgeVariants = cva(
  "h-5 gap-1 rounded-4xl border border-transparent px-2 py-0.5 text-xs font-medium transition-all has-data-[icon=inline-end]:pr-1.5 has-data-[icon=inline-start]:pl-1.5 [&>svg]:size-3! group/badge inline-flex w-fit shrink-0 items-center justify-center overflow-hidden whitespace-nowrap focus-visible:border-ring focus-visible:ring-3 focus-visible:ring-ring/50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 [&>svg]:pointer-events-none",
  {
  variants: {
    variant: {
      // Low Priority
      default:
        "bg-blue-50 text-blue-700 border-blue-300 hover:bg-blue-100",

      // Medium Priority
      secondary:
        "bg-yellow-50 text-yellow-700 border-yellow-300 hover:bg-yellow-100",

      // High Priority
      destructive:
        "bg-red-50 text-red-700 border-red-300 hover:bg-red-100",

      outline:
        "border-border text-foreground [a]:hover:bg-muted [a]:hover:text-muted-foreground",

      ghost:
        "hover:bg-muted hover:text-muted-foreground dark:hover:bg-muted/50",

      link:
        "text-primary underline-offset-4 hover:underline",
    },
  },
    defaultVariants: {
      variant: "default",
    },
  },
);