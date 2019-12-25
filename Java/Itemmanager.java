package de.kuschel_swein.utils;

import java.lang.reflect.Field;
import java.util.Arrays;
import org.bukkit.Material;
import org.bukkit.enchantments.Enchantment;
import org.bukkit.inventory.ItemStack;
import org.bukkit.inventory.meta.ItemMeta;









public class ItemManager
{
  public static ItemStack createItem(Material material, int anzahl, int subid, String displayname) {
    short neuesubid = (short)subid;
    ItemStack i = new ItemStack(material, anzahl, neuesubid);
    ItemMeta m = i.getItemMeta();
    m.setDisplayName(displayname);
    i.setItemMeta(m);
    
    return i;
  }

  
  public static ItemStack createItemWithLore(Material material, int anzahl, int subid, String displayname, String Lore) {
    short neuesubid = (short)subid;
    ItemStack i = new ItemStack(material, anzahl, neuesubid);
    ItemMeta m = i.getItemMeta();
    m.setDisplayName(displayname);
    m.setLore(Arrays.asList(new String[] { Lore }));
    i.setItemMeta(m);
    
    return i;
  }

  
  public static ItemStack createItemWithLoreMultiple(Material material, int anzahl, int subid, String displayname, String Lore, String Lore2, String Lore3) {
    short neuesubid = (short)subid;
    ItemStack i = new ItemStack(material, anzahl, neuesubid);
    ItemMeta m = i.getItemMeta();
    m.setDisplayName(displayname);
    m.setLore(Arrays.asList(new String[] { Lore, Lore2, Lore3 }));
    i.setItemMeta(m);
    
    return i;
  }

  public static ItemStack createItemEnch(Material material, int anzahl, int subid, String displayname, String lore, Enchantment enc, int level) {
    short neuesubid = (short)subid;
    ItemStack is = new ItemStack(material, anzahl, neuesubid);
    ItemMeta sm = is.getItemMeta();
    sm.setLore(Arrays.asList(new String[] { lore }));
    sm.setDisplayName(displayname);
    sm.addEnchant(enc, level, false);
    is.setItemMeta(sm);
    
    return is;
  }
