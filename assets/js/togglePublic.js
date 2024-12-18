async function togglePublic(id, checkbox) {
    const isPublic = checkbox.checked ? 1 : 0;
    if(!isPublic || confirm('本サイトに公開しますか？')){
        try {
            const response = await fetch('./toggle_public.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id, is_public: isPublic }),
            });
            if (!response.ok) {
                alert('公開状態の更新に失敗しました。');
                checkbox.checked = !isPublic;
            }
            if(isPublic){
                M.toast({html: '本サイトに公開しました',displayLength: 2000});
                publishedConfetti();
            }else{
                M.toast({html: '記事を非公開にしました',displayLength: 2000});
            }
        } catch (error) {
            alert('エラーが発生しました。');
            checkbox.checked = !isPublic;
        }
    }else{
        checkbox.checked = false;
    }
}