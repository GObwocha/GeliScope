import os
import re

def process_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # We will use regex to find style="something"
    # But wait, it's safer to do this carefully.
    
    # Match style="..." where it's not preceded by : (i.e. not :style)
    # Regex: (?<!:)style="([^"]+)"
    style_pattern = re.compile(r'(?<!:)style="([^"]+)"')
    
    matches = list(style_pattern.finditer(content))
    if not matches:
        return False
        
    print(f"Refactoring {filepath}, found {len(matches)} inline styles")
    
    styles_to_add = []
    
    # We must process from end to start to not mess up indices
    for i, match in reversed(list(enumerate(matches))):
        style_content = match.group(1).strip()
        class_name = f"extracted-style-{i+1}"
        
        styles_to_add.append(f".{class_name} {{\n  {style_content}\n}}")
        
        start, end = match.span()
        
        # We need to insert the class into the tag.
        # Find the tag boundaries.
        tag_start = content.rfind('<', 0, start)
        tag_end = content.find('>', start)
        
        tag_string = content[tag_start:tag_end+1]
        
        # Check if class="..." exists
        class_pattern = re.compile(r'(?<!:)class="([^"]*)"')
        class_match = class_pattern.search(tag_string)
        
        if class_match:
            # Append class
            old_class = class_match.group(1)
            new_class = f'{old_class} {class_name}' if old_class else class_name
            
            # replace inside tag_string
            new_tag_string = tag_string[:class_match.start(1)] + new_class + tag_string[class_match.end(1):]
            # remove style=""
            # Need to be careful to remove exactly the matched style="" inside the tag_string
            style_in_tag_start = tag_string.find(match.group(0))
            new_tag_string = new_tag_string[:style_in_tag_start] + new_tag_string[style_in_tag_start + len(match.group(0)):]
            
        else:
            # Insert class="class_name"
            # find where style="" is in tag_string
            style_in_tag_start = tag_string.find(match.group(0))
            new_tag_string = tag_string[:style_in_tag_start] + f'class="{class_name}" ' + tag_string[style_in_tag_start + len(match.group(0)):]
            
        content = content[:tag_start] + new_tag_string + content[tag_end+1:]
        
    
    if styles_to_add:
        # Check if <style scoped> exists
        style_block_pattern = re.compile(r'<style scoped>(.*?)</style>', re.DOTALL)
        style_match = style_block_pattern.search(content)
        
        styles_text = "\n\n".join(reversed(styles_to_add))
        
        if style_match:
            new_style_block = f"<style scoped>\n{styles_text}\n{style_match.group(1)}</style>"
            content = content[:style_match.start()] + new_style_block + content[style_match.end():]
        else:
            # Check if <style> exists without scoped
            style_nonscoped = re.compile(r'<style>(.*?)</style>', re.DOTALL)
            ns_match = style_nonscoped.search(content)
            if ns_match:
                new_style_block = f"<style>\n{styles_text}\n{ns_match.group(1)}</style>"
                content = content[:ns_match.start()] + new_style_block + content[ns_match.end():]
            else:
                # append to the end
                content += f"\n\n<style scoped>\n{styles_text}\n</style>\n"
                
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
        
    return True

components_dir = 'geliscope-frontend/src/components'
for filename in os.listdir(components_dir):
    if filename.endswith('.vue'):
        process_file(os.path.join(components_dir, filename))

print("Done")
